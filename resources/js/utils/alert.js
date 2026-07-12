import Swal from "sweetalert2";

// Configuration with your brand color
const CONFIG = {
    // Brand colors
    primaryColor: "#f47922", // Your brand color
    primaryLight: "#ff9c55", // Lighter variant
    primaryDark: "#c85a00",  // Darker variant

    // Bootstrap compatible colors
    successColor: "#198754",
    dangerColor: "#dc3545",
    warningColor: "#ffc107",
    infoColor: "#0dcaf0",
    secondaryColor: "#6c757d",

    // Timing
    toastDuration: 3000, // 3 seconds for toasts
    successAutoClose: 2500, // 2.5 seconds
    infoAutoClose: 3000, // 3 seconds
    warningAutoClose: 3500, // 3.5 seconds
};

/**
 * Display a success toast (auto-closes)
 */
export const showSuccessAlert = (message, title = "Success!", options = {}) => {
    const defaultOptions = {
        icon: "success",
        title: title,
        text: message,
        timer: CONFIG.successAutoClose,
        timerProgressBar: true,
        showConfirmButton: false,
        position: "top-end",
        toast: true,
        background: "#d1e7dd",
        color: "#0f5132",
        iconColor: CONFIG.successColor,
        showClass: {
            popup: 'animate__animated animate__fadeInDown'
        },
        hideClass: {
            popup: 'animate__animated animate__fadeOutUp'
        },
        didOpen: (toast) => {
            toast.addEventListener('mouseenter', Swal.stopTimer);
            toast.addEventListener('mouseleave', Swal.resumeTimer);
        }
    };

    return Swal.fire({ ...defaultOptions, ...options });
};

/**
 * Display an error alert (stays until clicked)
 */
export const showErrorAlert = (message, title = "Error!", options = {}) => {
    const defaultOptions = {
        icon: "error",
        title: title,
        text: message,
        showConfirmButton: true,
        confirmButtonText: "OK",
        confirmButtonColor: CONFIG.dangerColor,
        background: "#f8f9fa",
        color: "#212529",
        iconColor: CONFIG.dangerColor,
        allowOutsideClick: false,
        allowEscapeKey: true,
    };

    return Swal.fire({ ...defaultOptions, ...options });
};

/**
 * Display a warning toast (auto-closes)
 */
export const showWarningAlert = (message, title = "Warning!", options = {}) => {
    const defaultOptions = {
        icon: "warning",
        title: title,
        text: message,
        timer: CONFIG.warningAutoClose,
        timerProgressBar: true,
        showConfirmButton: false,
        position: "top-end",
        toast: true,
        background: "#fff3cd",
        color: "#664d03",
        iconColor: CONFIG.warningColor,
        showClass: {
            popup: 'animate__animated animate__fadeInDown'
        },
        hideClass: {
            popup: 'animate__animated animate__fadeOutUp'
        },
        didOpen: (toast) => {
            toast.addEventListener('mouseenter', Swal.stopTimer);
            toast.addEventListener('mouseleave', Swal.resumeTimer);
        }
    };

    return Swal.fire({ ...defaultOptions, ...options });
};

/**
 * Display an info toast (auto-closes)
 */
export const showInfoAlert = (message, title = "Info", options = {}) => {
    const defaultOptions = {
        icon: "info",
        title: title,
        text: message,
        timer: CONFIG.infoAutoClose,
        timerProgressBar: true,
        showConfirmButton: false,
        position: "top-end",
        toast: true,
        background: "#cff4fc",
        color: "#055160",
        iconColor: CONFIG.infoColor,
        showClass: {
            popup: 'animate__animated animate__fadeInDown'
        },
        hideClass: {
            popup: 'animate__animated animate__fadeOutUp'
        },
        didOpen: (toast) => {
            toast.addEventListener('mouseenter', Swal.stopTimer);
            toast.addEventListener('mouseleave', Swal.resumeTimer);
        }
    };

    return Swal.fire({ ...defaultOptions, ...options });
};

/**
 * Display a primary toast with brand color (auto-closes)
 */
export const showPrimaryAlert = (message, title = "", options = {}) => {
    const defaultOptions = {
        icon: "info",
        title: title,
        text: message,
        timer: CONFIG.successAutoClose,
        timerProgressBar: true,
        showConfirmButton: false,
        position: "top-end",
        toast: true,
        background: "rgba(244, 121, 34, 0.1)",
        color: "#7c3d10",
        iconColor: CONFIG.primaryColor,
        showClass: {
            popup: 'animate__animated animate__fadeInDown'
        },
        hideClass: {
            popup: 'animate__animated animate__fadeOutUp'
        },
        didOpen: (toast) => {
            toast.addEventListener('mouseenter', Swal.stopTimer);
            toast.addEventListener('mouseleave', Swal.resumeTimer);
        }
    };

    return Swal.fire({ ...defaultOptions, ...options });
};

/**
 * Display a confirmation dialog (requires user action)
 */
export const showConfirmationAlert = async (
    message,
    title = "Are you sure?",
    confirmText = "Yes",
    cancelText = "Cancel",
    options = {}
) => {
    const defaultOptions = {
        icon: "question",
        title: title,
        text: message,
        showCancelButton: true,
        confirmButtonText: confirmText,
        cancelButtonText: cancelText,
        confirmButtonColor: CONFIG.primaryColor,
        cancelButtonColor: CONFIG.secondaryColor,
        reverseButtons: true,
        focusCancel: false,
        background: "#f8f9fa",
        color: "#212529",
        iconColor: CONFIG.infoColor,
        allowOutsideClick: false,
        allowEscapeKey: true,
    };

    const result = await Swal.fire({ ...defaultOptions, ...options });
    return result.isConfirmed;
};

/**
 * Display a loading alert (requires manual closing)
 */
export const showLoadingAlert = (message = "Processing...") => {
    return Swal.fire({
        title: message,
        allowOutsideClick: false,
        allowEscapeKey: false,
        showConfirmButton: false,
        background: "#f8f9fa",
        color: "#212529",
        willOpen: () => {
            Swal.showLoading();
        },
        didOpen: () => {
            // Nothing needed
        }
    });
};

/**
 * Close the current alert
 */
export const closeAlert = () => {
    Swal.close();
};

/**
 * Display a toast notification (auto-closes)
 */
export const showToast = (message, type = "info", options = {}) => {
    const typeConfig = {
        success: {
            icon: "success",
            background: "#d1e7dd",
            color: "#0f5132",
            iconColor: CONFIG.successColor,
        },
        error: {
            icon: "error",
            background: "#f8d7da",
            color: "#842029",
            iconColor: CONFIG.dangerColor,
        },
        warning: {
            icon: "warning",
            background: "#fff3cd",
            color: "#664d03",
            iconColor: CONFIG.warningColor,
        },
        info: {
            icon: "info",
            background: "#cff4fc",
            color: "#055160",
            iconColor: CONFIG.infoColor,
        },
        primary: {
            icon: "info",
            background: "rgba(244, 121, 34, 0.1)",
            color: "#7c3d10",
            iconColor: CONFIG.primaryColor,
        }
    };

    const config = typeConfig[type] || typeConfig.info;

    const defaultOptions = {
        toast: true,
        position: "top-end",
        icon: config.icon,
        title: message,
        showConfirmButton: false,
        timer: CONFIG.toastDuration,
        timerProgressBar: true,
        background: config.background,
        color: config.color,
        iconColor: config.iconColor,
        showClass: {
            popup: 'animate__animated animate__fadeInDown'
        },
        hideClass: {
            popup: 'animate__animated animate__fadeOutUp'
        },
        didOpen: (toast) => {
            toast.addEventListener('mouseenter', Swal.stopTimer);
            toast.addEventListener('mouseleave', Swal.resumeTimer);
        }
    };

    return Swal.fire({ ...defaultOptions, ...options });
};

// Add Animate.css for animations
const addStyles = () => {
    if (!document.querySelector('#sweet-alert-styles')) {
        // Add Animate.css CDN
        const animateCss = document.createElement('link');
        animateCss.rel = 'stylesheet';
        animateCss.href = 'https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css';
        animateCss.id = 'animate-css';
        document.head.appendChild(animateCss);

        // Add custom styles
        const style = document.createElement('style');
        style.id = 'sweet-alert-styles';
        style.textContent = `
            /* Toast container stacking */
            .swal2-container.swal2-top-end {
                display: flex !important;
                flex-direction: column !important;
                align-items: flex-end !important;
                padding: 10px !important;
            }

            /* Toast spacing */
            .swal2-toast {
                margin: 0 0 10px 0 !important;
                min-width: 300px !important;
                max-width: 400px !important;
            }

            /* Progress bar styling */
            .swal2-timer-progress-bar {
                background: rgba(0, 0, 0, 0.2) !important;
                height: 3px !important;
            }

            /* Mobile responsiveness */
            @media (max-width: 576px) {
                .swal2-toast {
                    width: 90% !important;
                    max-width: 90% !important;
                    margin: 5px auto !important;
                }

                .swal2-container.swal2-top-end {
                    align-items: center !important;
                    padding: 5px !important;
                }
            }
        `;
        document.head.appendChild(style);
    }
};

// Initialize styles
if (typeof document !== "undefined") {
    addStyles();
}

/**
 * Grievance submission success modal
 * Shows ticket number with copy button and a Done & Refresh action.
 * @param {string} ticketNumber
 */
export const showGrievanceSuccess = async (ticketNumber) => {
    const copyToClipboard = async (text) => {
        try {
            if (navigator.clipboard?.writeText) {
                await navigator.clipboard.writeText(text);
                return true;
            }
            const ta = document.createElement('textarea');
            ta.value = text;
            ta.style.cssText = 'position:fixed;opacity:0';
            document.body.appendChild(ta);
            ta.focus(); ta.select();
            const ok = document.execCommand('copy');
            document.body.removeChild(ta);
            return ok;
        } catch { return false; }
    };

    const result = await Swal.fire({
        html: `
            <div class="gms-swal-body">
                <div class="gms-swal-check">
                    <svg viewBox="0 0 52 52"><circle class="gms-swal-circle" cx="26" cy="26" r="25" fill="none"/><path class="gms-swal-tick" fill="none" d="M14 27l8 8 16-16"/></svg>
                </div>
                <h2 class="gms-swal-title">Submitted Successfully!</h2>
                <p class="gms-swal-desc">Your grievance has been recorded. Use your ticket number to track the status.</p>
                <div class="gms-swal-ticket-wrap">
                    <span class="gms-swal-ticket-label">Ticket Number</span>
                    <div class="gms-swal-ticket-row">
                        <span class="gms-swal-ticket-num" id="swal-ticket-num">${ticketNumber}</span>
                        <button type="button" class="gms-swal-copy-btn" id="swal-copy-btn">
                            <i class="bi bi-copy" id="swal-copy-icon"></i>
                            <span id="swal-copy-text">Copy</span>
                        </button>
                    </div>
                </div>
            </div>
        `,
        showConfirmButton: true,
        confirmButtonText: '<i class="bi bi-check-circle-fill me-1"></i> Done',
        showCancelButton: false,
        allowOutsideClick: false,
        allowEscapeKey: false,
        showClass: {
            popup: 'gms-swal-fade-in'
        },
        hideClass: {
            popup: 'gms-swal-fade-out'
        },
        customClass: {
            popup:         'gms-swal-popup',
            confirmButton: 'gms-swal-done-btn',
        },
        didOpen: () => {
            const copyBtn  = document.getElementById('swal-copy-btn');
            const copyIcon = document.getElementById('swal-copy-icon');
            const copyText = document.getElementById('swal-copy-text');
            copyBtn?.addEventListener('click', async () => {
                const ok = await copyToClipboard(ticketNumber);
                if (ok) {
                    copyIcon.className = 'bi bi-check2';
                    copyText.textContent = 'Copied!';
                    copyBtn.classList.add('copied');
                    setTimeout(() => {
                        copyIcon.className = 'bi bi-copy';
                        copyText.textContent = 'Copy';
                        copyBtn.classList.remove('copied');
                    }, 2000);
                }
            });
        },
    });
};

// Export default object
export default {
    success: showSuccessAlert,
    error: showErrorAlert,
    warning: showWarningAlert,
    info: showInfoAlert,
    primary: showPrimaryAlert,
    confirm: showConfirmationAlert,
    loading: showLoadingAlert,
    close: closeAlert,
    toast: showToast,
    grievanceSuccess: showGrievanceSuccess,
};
