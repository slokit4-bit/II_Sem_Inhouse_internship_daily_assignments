/* ==========================================================
   Student Result Viewer System - script.js
   Basic client-side validation.
   NOTE: This is just a first check for the user's convenience.
   The REAL, secure validation always happens again in PHP,
   because JavaScript can be disabled or bypassed by anyone.
   ========================================================== */

// Runs once the page has fully loaded
document.addEventListener("DOMContentLoaded", function () {

    // ---------- Student Registration Form ----------
    const regForm = document.getElementById("registerForm");
    if (regForm) {
        regForm.addEventListener("submit", function (e) {
            const name = document.getElementById("name").value.trim();
            const roll = document.getElementById("roll_no").value.trim();
            const dob = document.getElementById("dob").value;
            const studentClass = document.getElementById("class").value.trim();

            if (name === "" || roll === "" || dob === "" || studentClass === "") {
                alert("Please fill all the required fields.");
                e.preventDefault();
                return false;
            }
        });
    }

    // ---------- Student Login Form ----------
    const studentLoginForm = document.getElementById("studentLoginForm");
    if (studentLoginForm) {
        studentLoginForm.addEventListener("submit", function (e) {
            const roll = document.getElementById("roll_no").value.trim();
            if (roll === "") {
                alert("Please enter your Roll Number.");
                e.preventDefault();
                return false;
            }
        });
    }

    // ---------- Admin Login Form ----------
    const adminLoginForm = document.getElementById("adminLoginForm");
    if (adminLoginForm) {
        adminLoginForm.addEventListener("submit", function (e) {
            const username = document.getElementById("username").value.trim();
            const password = document.getElementById("password").value.trim();
            if (username === "" || password === "") {
                alert("Please enter both username and password.");
                e.preventDefault();
                return false;
            }
        });
    }

    // ---------- Delete confirmation (used on admin pages) ----------
    const deleteLinks = document.querySelectorAll(".confirm-delete");
    deleteLinks.forEach(function (link) {
        link.addEventListener("click", function (e) {
            const sure = confirm("Are you sure you want to delete this record? This cannot be undone.");
            if (!sure) {
                e.preventDefault();
            }
        });
    });

    // ---------- Add Result Form: auto-calculate total & percentage on the fly ----------
    const marksInputs = document.querySelectorAll(".marks-input");
    const totalDisplay = document.getElementById("livePercentage");

    if (marksInputs.length > 0 && totalDisplay) {
        marksInputs.forEach(function (input) {
            input.addEventListener("input", calculateLiveTotal);
        });
    }

    function calculateLiveTotal() {
        let obtained = 0;
        let max = 0;
        document.querySelectorAll(".obtained-marks").forEach(function (input) {
            obtained += Number(input.value) || 0;
        });
        document.querySelectorAll(".max-marks").forEach(function (input) {
            max += Number(input.value) || 0;
        });
        if (max > 0) {
            const percent = ((obtained / max) * 100).toFixed(2);
            totalDisplay.textContent = "Total: " + obtained + " / " + max + "  (" + percent + "%)";
        }
    }
});
