const csrfToken = document
    .querySelector('meta[name="csrf-token"]')
    .getAttribute("content");

async function sendRequest(url, method, data = null) {
    const options = {
        method,
        headers: {
            Accept: "application/json",
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": csrfToken,
        },
    };

    if (data !== null) {
        options.body = JSON.stringify(data);
    }

    const response = await fetch(url, options);

    let responseData = {};

    try {
        responseData = await response.json();
    } catch {
        responseData = {};
    }

    if (!response.ok) {
        throw {
            status: response.status,
            data: responseData,
        };
    }

    return responseData;
}

function showSuccess(message) {
    const element = document.getElementById("success-message");

    element.textContent = message;
    element.classList.remove("hidden");

    document.getElementById("error-message").classList.add("hidden");
}

function showError(message) {
    const element = document.getElementById("error-message");

    element.textContent = message;
    element.classList.remove("hidden");

    document.getElementById("success-message").classList.add("hidden");
}

function getValidationMessage(data) {
    if (!data.errors) {
        return data.message ?? 'Validation failed.';
    }

    const firstField = Object.keys(data.errors)[0];

    return data.errors[firstField][0];
}


const addMemberForm = document.getElementById('add-member-form');

if (addMemberForm) {
    addMemberForm.addEventListener('submit', async function (event) {
        event.preventDefault();

        const organizationId =
            this.dataset.organizationId;

        const button =
            document.getElementById('add-member-button');

        const userId =
            document.getElementById('user_id').value;

        const role =
            document.getElementById('role').value;

        button.disabled = true;
        button.textContent = 'Adding...';

        try {
            const response = await sendRequest(
                `/organizations/${organizationId}/members`,
                'POST',
                {
                    user_id: Number(userId),
                    role: role,
                }
            );

            showSuccess(response.message);

            this.reset();

            window.location.reload();

        } catch (error) {

            if (error.status === 403) {
                showError(
                    'You are not authorized to manage members.'
                );
            } else if (error.status === 422) {
                showError(
                    getValidationMessage(error.data)
                );
            } else {
                showError(
                    'Something went wrong. Please try again.'
                );
            }

        } finally {
            button.disabled = false;
            button.textContent = 'Add Member';
        }
    });
}



document.querySelectorAll('.member-role')
    .forEach(function (select) {

        select.addEventListener('change', async function () {

            const organizationId =
                addMemberForm.dataset.organizationId;

            const userId =
                this.dataset.userId;

            const role =
                this.value;

            const originalValue =
                this.dataset.previousValue ?? 'member';

            this.disabled = true;

            try {

                const response = await sendRequest(
                    `/organizations/${organizationId}/members/${userId}`,
                    'PATCH',
                    {
                        role: role,
                    }
                );

                this.dataset.previousValue = role;

                showSuccess(response.message);

            } catch (error) {

                this.value = originalValue;

                if (error.status === 403) {
                    showError(
                        'You are not authorized to change member roles.'
                    );
                } else if (error.status === 422) {
                    showError(
                        getValidationMessage(error.data)
                    );
                } else {
                    showError(
                        'Unable to update member role.'
                    );
                }

            } finally {
                this.disabled = false;
            }
        });
    });