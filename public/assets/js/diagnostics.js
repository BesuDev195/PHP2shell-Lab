/**
 * CTC Bank — Infrastructure Diagnostics Console Controller
 */

document.addEventListener('DOMContentLoaded', () => {
    const targetInput = document.getElementById('diagTargetInput');
    const diagForm = document.getElementById('diagForm');
    const runBtn = document.getElementById('btnRunDiagnostic');
    const runBtnText = document.getElementById('runBtnText');
    const runBtnSpinner = document.getElementById('runBtnSpinner');
    const copyOutputBtn = document.getElementById('btnCopyTerminal');
    const terminalOutput = document.getElementById('terminalOutputContent');

    // 1. Preset Button Handlers
    const presetButtons = document.querySelectorAll('[data-target-preset]');
    presetButtons.forEach(btn => {
        btn.addEventListener('click', (e) => {
            e.preventDefault();
            const val = btn.getAttribute('data-target-preset');
            if (targetInput) {
                targetInput.value = val;
                targetInput.focus();
            }
        });
    });

    // 2. Loading State on Form Submission
    if (diagForm && runBtn) {
        diagForm.addEventListener('submit', () => {
            runBtn.disabled = true;
            if (runBtnText) runBtnText.textContent = 'Executing Diagnostic...';
            if (runBtnSpinner) runBtnSpinner.style.display = 'inline-block';
        });
    }

    // 3. Copy Terminal Output
    if (copyOutputBtn && terminalOutput) {
        copyOutputBtn.addEventListener('click', () => {
            const textToCopy = terminalOutput.innerText || terminalOutput.textContent;
            window.copyToClipboard(textToCopy, copyOutputBtn);
        });
    }
});
