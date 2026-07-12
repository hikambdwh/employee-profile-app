import "./bootstrap";
import "./dashboard-live-search";
import "flowbite";

document.addEventListener("DOMContentLoaded", () => {
    initEmployeeForm();
});

function initEmployeeForm() {
    const form = document.querySelector("[data-employee-form]");
    if (!form) return;

    const panels = Array.from(form.querySelectorAll("[data-form-step]"));
    const stepButtons = Array.from(document.querySelectorAll("[data-step-button]"));
    const previousButton = form.querySelector("[data-previous-step]");
    const nextButton = form.querySelector("[data-next-step]");
    const submitButton = form.querySelector("[data-submit-form]");
    const currentStepText = document.querySelector("[data-current-step]");
    const progressText = document.querySelector("[data-form-progress-text]");
    const progressBar = document.querySelector("[data-form-progress-bar]");
    const progressCount = document.querySelector("[data-form-progress-count]");
    const requiredFields = Array.from(
        form.querySelectorAll("input[required], select[required], textarea[required]")
    ).filter((field) => field.name && field.name !== "_token");

    let currentStep = 0;
    let isSubmitting = false;

    const panelWithError = panels.findIndex((panel) => panel.querySelector(".kanmo-error"));
    if (panelWithError >= 0) currentStep = panelWithError;

    const fieldHasValue = (field) => {
        if (field.type === "checkbox" || field.type === "radio") return field.checked;
        return String(field.value ?? "").trim() !== "";
    };

    const updateCompletion = () => {
        const completed = requiredFields.filter(fieldHasValue).length;
        const total = requiredFields.length;
        const percentage = total > 0 ? Math.round((completed / total) * 100) : 0;

        if (progressText) progressText.textContent = `${percentage}%`;
        if (progressCount) progressCount.textContent = `${completed} dari ${total} field wajib telah diisi`;
        if (progressBar) {
            progressBar.style.width = `${percentage}%`;
            progressBar.setAttribute("aria-valuenow", String(percentage));
        }
    };

    const markStepStates = () => {
        stepButtons.forEach((button, index) => {
            const panel = panels[index];
            const fields = panel
                ? Array.from(panel.querySelectorAll("input[required], select[required], textarea[required]"))
                : [];
            const stepComplete = fields.length > 0 && fields.every(fieldHasValue);

            button.dataset.state = index === currentStep
                ? "active"
                : stepComplete
                  ? "complete"
                  : "idle";

            button.setAttribute("aria-current", index === currentStep ? "step" : "false");
        });
    };

    const showStep = (index, shouldFocus = false) => {
        currentStep = Math.min(Math.max(index, 0), panels.length - 1);

        panels.forEach((panel, panelIndex) => {
            const isActive = panelIndex === currentStep;
            panel.dataset.active = isActive ? "true" : "false";
            panel.setAttribute("aria-hidden", isActive ? "false" : "true");
        });

        if (currentStepText) currentStepText.textContent = `Langkah ${currentStep + 1} dari ${panels.length}`;
        previousButton?.classList.toggle("hidden", currentStep === 0);
        nextButton?.classList.toggle("hidden", currentStep === panels.length - 1);
        submitButton?.classList.toggle("hidden", currentStep !== panels.length - 1);
        markStepStates();

        if (shouldFocus) {
            panels[currentStep]
                ?.querySelector("input:not([readonly]), select, textarea")
                ?.focus({ preventScroll: true });
            panels[currentStep]?.scrollIntoView({ behavior: "smooth", block: "start" });
        }
    };

    const validateCurrentStep = () => {
        const fields = Array.from(
            panels[currentStep].querySelectorAll("input, select, textarea")
        ).filter((field) => !field.disabled && field.type !== "hidden");

        for (const field of fields) {
            if (!field.checkValidity()) {
                field.reportValidity();
                field.focus();
                return false;
            }
        }
        return true;
    };

    nextButton?.addEventListener("click", () => {
        if (validateCurrentStep()) showStep(currentStep + 1, true);
    });

    previousButton?.addEventListener("click", () => showStep(currentStep - 1, true));

    stepButtons.forEach((button, targetStep) => {
        button.addEventListener("click", () => {
            if (targetStep > currentStep && !validateCurrentStep()) return;
            showStep(targetStep, true);
        });
    });

    requiredFields.forEach((field) => {
        ["input", "change"].forEach((eventName) => {
            field.addEventListener(eventName, () => {
                updateCompletion();
                markStepStates();
            });
        });
    });

    const copyAddressCheckbox = form.querySelector("[data-copy-address]");
    const currentAddress = form.querySelector("#current_address");
    const ktpAddress = form.querySelector("#ktp_address");

    const copyAddress = () => {
        if (!copyAddressCheckbox?.checked || !currentAddress || !ktpAddress) return;
        ktpAddress.value = currentAddress.value;
        ktpAddress.dispatchEvent(new Event("input", { bubbles: true }));
    };

    copyAddressCheckbox?.addEventListener("change", copyAddress);
    currentAddress?.addEventListener("input", copyAddress);

    const syncButton = form.querySelector("[data-sync-button]");
    const employeeIdInput = form.querySelector("#employee_id");

    syncButton?.addEventListener("click", () => {
        const employeeId = employeeIdInput?.value.trim();
        if (!employeeId) {
            employeeIdInput?.setCustomValidity("Employee ID wajib diisi sebelum melakukan sinkronisasi.");
            employeeIdInput?.reportValidity();
            employeeIdInput?.setCustomValidity("");
            return;
        }

        syncButton.disabled = true;
        syncButton.querySelector("[data-sync-label]").textContent = "Menyinkronkan...";
        syncButton.querySelector("[data-sync-spinner]")?.classList.remove("hidden");

        const url = new URL(window.location.href);
        url.searchParams.set("employee_id", employeeId);
        window.location.assign(url.toString());
    });

    form.addEventListener("submit", (event) => {
        if (isSubmitting) {
            event.preventDefault();
            return;
        }

        if (!form.checkValidity()) {
            event.preventDefault();
            const invalidField = form.querySelector(":invalid");
            const invalidStep = panels.findIndex((panel) => panel.contains(invalidField));
            if (invalidStep >= 0) showStep(invalidStep, true);
            invalidField?.reportValidity();
            return;
        }

        isSubmitting = true;
        if (submitButton) {
            submitButton.disabled = true;
            submitButton.querySelector("[data-submit-label]").textContent = "Menyimpan data...";
            submitButton.querySelector("[data-submit-spinner]")?.classList.remove("hidden");
        }
    });

    updateCompletion();
    showStep(currentStep);
}
