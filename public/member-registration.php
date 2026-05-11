<?php

?>
<!DOCTYPE html>
<html lang="en">
<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Member Registration</title>

    <!-- Bootstrap -->
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    <!-- Bootstrap Icons -->
    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"
    >

    <!-- Theme -->
    <link
        href="/assets/css/theme.css"
        rel="stylesheet"
    >

    <style>

        body {

            background:
                #f4f6f9;
        }

        /*
        |--------------------------------------------------------------------------
        | Topbar
        |--------------------------------------------------------------------------
        */
        .topbar {

            height: 72px;

            background:
                linear-gradient(
                    135deg,
                    var(--primary-bg),
                    var(--secondary-bg)
                );

            display: flex;

            align-items: center;

            justify-content: space-between;

            padding: 0 28px;

            color: white;

            box-shadow:
                0 4px 14px rgba(0,0,0,0.08);
        }

        .brand {

            display: flex;

            align-items: center;

            gap: 14px;
        }

        .brand-logo {

            width: 44px;
            height: 44px;

            border-radius: 12px;

            background:
                rgba(255,255,255,0.12);

            display: flex;

            align-items: center;

            justify-content: center;

            font-size: 22px;
        }

        .brand-title {

            font-size: 20px;
            font-weight: 700;
        }

        .brand-subtitle {

            font-size: 12px;

            opacity: 0.82;
        }

        /*
        |--------------------------------------------------------------------------
        | Layout
        |--------------------------------------------------------------------------
        */
        .app-layout {

            padding: 28px;
        }

        .dashboard-panel {

            background: white;

            border-radius: 24px;

            padding: 28px;

            box-shadow:
                0 8px 24px rgba(0,0,0,0.05);

            margin-bottom: 24px;
        }

        .panel-title {

            font-size: 20px;
            font-weight: 700;

            margin-bottom: 24px;

            color:
                var(--primary-bg);

            display: flex;

            align-items: center;

            gap: 10px;
        }

        /*
        |--------------------------------------------------------------------------
        | Sidebar
        |--------------------------------------------------------------------------
        */
        .profile-sidebar {

            background: white;

            border-radius: 24px;

            overflow: hidden;

            box-shadow:
                0 8px 24px rgba(0,0,0,0.05);
        }

        .profile-cover {

            height: 120px;

            background:
                linear-gradient(
                    135deg,
                    var(--primary-bg),
                    var(--secondary-bg)
                );
        }

        .profile-body {

            padding: 0 28px 28px;

            text-align: center;
        }

        .profile-photo {

            width: 130px;
            height: 130px;

            border-radius: 50%;

            object-fit: cover;

            border: 6px solid white;

            margin-top: -65px;

            background: #efefef;
        }

        .member-name {

            font-size: 24px;
            font-weight: 700;

            margin-top: 18px;
        }

        .member-role {

            color: #777;

            margin-top: 6px;
        }

        .sidebar-actions {

            margin-top: 28px;

            display: grid;

            gap: 12px;
        }

        /*
        |--------------------------------------------------------------------------
        | Form
        |--------------------------------------------------------------------------
        */
        .form-label {

            font-weight: 600;

            margin-bottom: 8px;

            color: #444;
        }

        .form-control,
        .form-select {

            border-radius: 14px;

            border:
                1px solid #dcdcdc;

            padding: 12px 14px;

            min-height: 50px;
        }

        .form-control:focus,
        .form-select:focus {

            border-color:
                var(--primary-bg);

            box-shadow:
                0 0 0 0.2rem rgba(149,21,15,0.12);
        }

        textarea.form-control {

            min-height: 120px;
        }

        /*
        |--------------------------------------------------------------------------
        | Stats
        |--------------------------------------------------------------------------
        */
        .stats-card {

            background:
                linear-gradient(
                    135deg,
                    #fff,
                    #fff5f4
                );

            border-radius: 18px;

            padding: 20px;

            border:
                1px solid #f1e2e0;
        }

        .stats-label {

            font-size: 12px;

            text-transform: uppercase;

            color: #777;

            letter-spacing: 0.5px;
        }

        .stats-value {

            font-size: 28px;
            font-weight: 800;

            margin-top: 8px;

            color:
                var(--primary-bg);
        }

        /*
        |--------------------------------------------------------------------------
        | Upload Box
        |--------------------------------------------------------------------------
        */
        .upload-box {

            border:
                2px dashed #d8d8d8;

            border-radius: 18px;

            padding: 24px;

            text-align: center;

            background:
                #fafafa;
        }

        /*
        |--------------------------------------------------------------------------
        | Loading
        |--------------------------------------------------------------------------
        */
        .loading-overlay {

            position: fixed;

            inset: 0;

            background:
                rgba(255,255,255,0.7);

            display: none;

            align-items: center;

            justify-content: center;

            z-index: 9999;
        }

        /*
        |--------------------------------------------------------------------------
        | Responsive
        |--------------------------------------------------------------------------
        */
        @media(max-width: 992px) {

            .app-layout {

                padding: 18px;
            }

            .dashboard-panel {

                padding: 22px;
            }
        }

    </style>

</head>
<body>

<!-- TOPBAR -->
<div class="topbar">

    <div class="brand">

        <div class="brand-logo">
            <!-- <i class="bi bi-person-plus"></i> -->
            <img src="/assets/images/ssckpca.png" alt="Logo" class="img-fluid" style="">
        </div>

        <div>

            <div class="brand-title">
                PCA Member Portal
            </div>

            <div class="brand-subtitle">
                New Member Enrollment Workspace
            </div>

        </div>

    </div>

    <div>

        <button
            class="btn btn-light"
            onclick="history.back()"
        >
            <i class="bi bi-arrow-left"></i>
            Back
        </button>

    </div>

</div>

<!-- APP -->
<div class="app-layout">

    <div class="row g-4">

        <!-- SIDEBAR -->
        <!-- <div class="col-lg-4"> -->

            <!-- <div class="profile-sidebar"> -->

                <!-- <div class="profile-cover"></div> -->

                <!-- <div class="profile-body"> -->

                    <!-- <img
                        id="profile-preview"
                        class="profile-photo"
                        src="https://via.placeholder.com/130"
                    > -->

                    <!-- <div
                        class="member-name"
                        id="preview-name"
                    >
                        New Member
                    </div> -->

                    <!-- <div class="member-role">
                        Pending Registration
                    </div> -->

                    <!-- <div class="sidebar-actions"> -->

                        <!-- <button
                            type="button"
                            class="btn btn-primary-theme"
                            onclick="submitRegistration()"
                        >
                            <i class="bi bi-check-circle"></i>
                            Register Member
                        </button> -->

                        <!-- <button
                            type="button"
                            class="btn btn-secondary-theme"
                            onclick="resetRegistrationForm()"
                        >
                            <i class="bi bi-arrow-clockwise"></i>
                            Reset Form
                        </button> -->

                    <!-- </div> -->

                <!-- </div> -->

            <!-- </div> -->

            <!-- SUMMARY -->
            <!-- <div class="dashboard-panel mt-4">

                <div class="panel-title">

                    <i class="bi bi-bar-chart"></i>

                    Registration Summary

                </div>

                <div class="stats-card mb-3">

                    <div class="stats-label">
                        Membership Year
                    </div>

                    <div
                        class="stats-value"
                        id="membership-year-preview"
                    >
                        <?php echo date('Y'); ?>
                    </div>

                </div>

                <div class="stats-card">

                    <div class="stats-label">
                        Registration Status
                    </div>

                    <div class="stats-value">
                        NEW
                    </div>

                </div>

            </div> -->

        <!-- </div> -->


        <!-- SIDEBAR -->
        <div class="col-lg-4">

            <div class="profile-sidebar">

                <!-- <div class="profile-cover"></div> -->

                <div class="profile-body">
                    <div
                        class="member-name"
                        id="preview-name"
                    >
                        Membership Request
                    </div>

                    <div class="member-role">
                        Pending Registration
                    </div>

                        <button
                            type="button"
                            class="btn btn-primary-theme"
                            onclick="submitRegistration()"
                        >
                            <i class="bi bi-check-circle"></i>
                            Submit Application Form
                        </button>

                </div>
            </div>
        </div>

        <!-- MAIN -->
        <div class="col-lg-8">

            <!-- PERSONAL -->
            <div class="dashboard-panel">

                <div class="panel-title">

                    <i class="bi bi-person-vcard"></i>

                    Personal Information

                </div>

                <form id="registration-form">

                    <div class="row g-4">

                        <div class="col-md-6">

                            <label class="form-label">
                                First Name
                            </label>

                            <input
                                type="text"
                                id="first_name"
                                class="form-control"
                                required
                            >

                        </div>

                        <div class="col-md-6">

                            <label class="form-label">
                                Last Name
                            </label>

                            <input
                                type="text"
                                id="last_name"
                                class="form-control"
                                required
                            >

                        </div>

                        <div class="col-md-6">

                            <label class="form-label">
                                Phone Number
                            </label>

                            <input
                                type="text"
                                id="phone"
                                class="form-control"
                            >

                        </div>

                        <div class="col-md-6">

                            <label class="form-label">
                                NIC
                            </label>

                            <input
                                type="text"
                                id="nic"
                                class="form-control"
                            >

                        </div>

                        <div class="col-md-6">

                            <label class="form-label">
                                Birthday
                            </label>

                            <input
                                type="date"
                                id="birthday"
                                class="form-control"
                            >

                        </div>

                    </div>

                </form>

            </div>

            <!-- MEMBERSHIP -->
            <div class="dashboard-panel">

                <div class="panel-title">

                    <i class="bi bi-mortarboard"></i>

                    Alumni Information

                </div>

                <div class="row g-4">

                    <div class="col-md-6">

                        <label class="form-label">
                            Batch Year
                        </label>

                        <input
                            type="number"
                            id="batch_year"
                            class="form-control"
                        >

                    </div>

                    <div class="col-md-6">

                        <label class="form-label">
                            Cricket Years
                        </label>

                        <input
                            type="text"
                            id="cricket_years"
                            class="form-control"
                            placeholder="2000,2001,2002"
                        >

                    </div>

                </div>

            </div>

            <!-- MEMBERSHIP FEE PHOTO -->
            <div class="dashboard-panel">

                <div class="panel-title">

                    <i class="bi bi-image"></i>

                   Membership Registration Fee

                </div>

                <div class="upload-box">

                    <input
                        type="file"
                        id="member_registration_fee"
                        class="form-control"
                        accept="image/*"
                    >

                    <div class="mt-3 text-muted">
                        Registration Fee Payment Record
                    </div>

                </div>
            </div>

        </div>

    </div>

</div>

<!-- LOADING -->
<div
    id="loading-overlay"
    class="loading-overlay"
>

    <div
        class="spinner-border text-danger"
        role="status"
    >
    </div>

</div>

<script>

/*
|--------------------------------------------------------------------------
| Live Preview
|--------------------------------------------------------------------------
*/
// document
//     .getElementById('full_name')
//     .addEventListener(
//         'input',
//         function () {

//             document.getElementById(
//                 'preview-name'
//             ).innerHTML =
//                 this.value || 'New Member';
//         }
//     );

/*
|--------------------------------------------------------------------------
| Profile Photo Preview
|--------------------------------------------------------------------------
*/
// document
//     .getElementById('profile_photo')
//     .addEventListener(
//         'change',
//         function(event) {

//             const file =
//                 event.target.files[0];

//             if (!file) {
//                 return;
//             }

//             const reader =
//                 new FileReader();

//             reader.onload =
//                 function(e) {

//                     document.getElementById(
//                         'profile-preview'
//                     ).src =
//                         e.target.result;
//                 };

//             reader.readAsDataURL(file);
//         }
//     );

/*
|--------------------------------------------------------------------------
| Submit Registration
|--------------------------------------------------------------------------
*/
async function submitRegistration()
{
    try {

        showLoading();

        const formData =
            new FormData();

        formData.append(
            'first_name',
            document.getElementById(
                'first_name'
            ).value
        );

        formData.append(
            'last_name',
            document.getElementById(
                'last_name'
            ).value
        );

        formData.append(
            'phone',
            document.getElementById(
                'phone'
            ).value
        );

        formData.append(
            'nic',
            document.getElementById(
                'nic'
            ).value
        );

        formData.append(
            'birthday',
            document.getElementById(
                'birthday'
            ).value
        );

        formData.append(
            'batch_year',
            document.getElementById(
                'batch_year'
            ).value
        );

        formData.append(
            'cricket_years',
            document.getElementById(
                'cricket_years'
            ).value
        );


        const membershipFee =
            document.getElementById(
                'member_registration_fee'
            ).files[0];

        if (membershipFee) {

            formData.append(
                'member_registration_fee',
                membershipFee
            );
        }

        const response =
            await fetch(
                '/v1/member/membership-request',
                {
                    method: 'POST',
                    body: formData
                }
            );

        const result =
            await response.json();

        hideLoading();

        if (result.success) {

            alert(
                'Member registered successfully'
            );
            // window.location.href =
            //     `/member.php?id=${result.data.id}`;

        } else {

            alert(
                result.message ||
                'Registration failed'
            );
        }

    } catch (error) {

        console.error(error);

        hideLoading();

        alert(
            'Network error occurred'
        );
    }
}

/*
|--------------------------------------------------------------------------
| Reset
|--------------------------------------------------------------------------
*/
function resetRegistrationForm()
{
    document
        .getElementById(
            'registration-form'
        )
        .reset();

    document.getElementById(
        'preview-name'
    ).innerHTML =
        'New Member';

    document.getElementById(
        'profile-preview'
    ).src =
        'https://via.placeholder.com/130';
}

/*
|--------------------------------------------------------------------------
| Loading
|--------------------------------------------------------------------------
*/
function showLoading()
{
    document.getElementById(
        'loading-overlay'
    ).style.display =
        'flex';
}

function hideLoading()
{
    document.getElementById(
        'loading-overlay'
    ).style.display =
        'none';
}

</script>

</body>
</html>