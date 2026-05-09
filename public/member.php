<?php
$memberId = $_GET['id'] ?? '';

if (empty($memberId)) {
    die("Invalid member ID");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Member Profile</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="/assets/css/theme.css" rel="stylesheet"/>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f4f4;
            margin: 0;
            padding: 20px;
        }

        .profile-container {
            max-width: 1000px;
            margin: auto;
            background: #fff;
            border-radius: 10px;
            padding: 30px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.08);
        }

        .profile-header {
            display: flex;
            gap: 30px;
            align-items: center;
            border-bottom: 1px solid #ddd;
            padding-bottom: 20px;
            margin-bottom: 20px;
        }

        .profile-photo {
            width: 140px;
            height: 140px;
            border-radius: 50%;
            overflow: hidden;
            background: #eee;
        }

        .profile-photo img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .member-name {
            font-size: 30px;
            font-weight: bold;
        }

        .member-status {
            margin-top: 10px;
            display: inline-block;
            padding: 5px 10px;
            border-radius: 5px;
            background: #ddd;
        }

        .profile-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit,minmax(250px,1fr));
            gap: 20px;
        }

        .info-card {
            background: #fafafa;
            border: 1px solid #eee;
            border-radius: 8px;
            padding: 15px;
        }

        .info-label {
            font-size: 12px;
            color: #666;
            margin-bottom: 5px;
        }

        .info-value {
            font-size: 16px;
            font-weight: bold;
        }

        .error-box {
            background: #ffe5e5;
            color: #a00000;
            padding: 15px;
            border-radius: 8px;
        }

        .loading {
            text-align: center;
            padding: 50px;
        }

        :root {

            --primary-bg: #95150F;
            --secondary-bg: #6F100B;
            --light-bg: #F6A6A2;

            --white: #ffffff;
            --dark-text: #2b2b2b;
        }

        body {

            background:
                linear-gradient(
                    135deg,
                    var(--primary-bg),
                    var(--secondary-bg)
                );

            min-height: 100vh;

            display: flex;
            align-items: center;
            justify-content: center;

            padding: 20px;

            font-family:
                Inter,
                Arial,
                sans-serif;
        }

.light-bg {
background-color: #F2E2BA; 
}

.primary-bg {
background-color: #95150F; 
}



       

        .portal-wrapper {

            width: 100%;
            max-width: 1100px;

            background: var(--white);

            border-radius: 20px;

            overflow: hidden;

            box-shadow:
                0 10px 40px rgba(0,0,0,0.25);
        }

        .left-panel {

            background:
                linear-gradient(
                    180deg,
                    var(--primary-bg),
                    var(--secondary-bg)
                );

            color: var(--white);

            padding: 60px;

            height: 100%;
        }

        .brand-title {

            font-size: 42px;
            font-weight: 700;
            line-height: 1.2;
        }

        .brand-subtitle {

            margin-top: 20px;

            font-size: 18px;

            opacity: 0.9;
        }

        .feature-list {

            margin-top: 40px;
        }

        .feature-item {

            margin-bottom: 16px;

            padding-left: 28px;

            position: relative;
        }

        .feature-item::before {

            content: "•";

            position: absolute;

            left: 0;

            font-size: 22px;
        }

        .right-panel {

            padding: 60px;
            background: #fff;
        }

        .login-title {

            font-size: 34px;
            font-weight: 700;

            color: var(--primary-bg);
        }

        .login-subtitle {

            margin-top: 10px;

            color: #666;
        }

        .form-control {

            border-radius: 12px;

            padding: 14px;

            border: 1px solid #ddd;
        }

        .form-control:focus {

            border-color: var(--primary-bg);

            box-shadow:
                0 0 0 0.2rem rgba(149,21,15,0.15);
        }

        .btn-login {

            background: var(--primary-bg);

            color: var(--white);

            border: none;

            border-radius: 12px;

            padding: 14px;

            font-weight: 600;

            transition: 0.2s;
        }

        .btn-login:hover {

            background: var(--secondary-bg);
        }

        .quick-links {

            margin-top: 30px;
        }

        .quick-links a {

            color: var(--primary-bg);

            text-decoration: none;

            font-weight: 500;
        }

        .quick-links a:hover {

            text-decoration: underline;
        }

        .footer-text {

            margin-top: 30px;

            font-size: 13px;

            color: #888;
        }

        .school-badge {

            display: inline-block;

            background: rgba(255,255,255,0.1);

            padding: 10px 16px;

            border-radius: 999px;

            margin-bottom: 25px;

            font-size: 14px;
        }

        @media(max-width: 768px) {

            .left-panel,
            .right-panel {

                padding: 35px;
            }

            .brand-title {

                font-size: 32px;
            }
        } 



    </style>
</head>
<body>

<div class="container">

<!-- <div class="page-container"> -->

        
<div class="page-header">
    <div class="">
        <img src="/assets/images/ssckpca.png" alt="Logo" class="img-fluid" style="height:50px;">
    </div>    
    <div>
        <div class="page-title">
        Member Info Card
        </div>
    </div>
</div>

<div class="page-content light-bg">
                
    <div class="profile-grid">

                    <div class="info-card">
                        <div class="member-name" id="member-name"></div>
                        <div class="member-status" id="member-status"></div>
                        <div style="margin-top:10px;">
                            Member No:
                            <strong id="member-no"></strong>
                        </div>
                    </div>

                    <div class="info-card">
                        <div class="info-label">Email</div>
                        <div class="info-value" id="email"></div>
                    </div>

                    <div class="info-card">
                        <div class="info-label">Phone</div>
                        <div class="info-value" id="phone"></div>
                    </div>

                    <div class="info-card">
                        <div class="info-label">NIC</div>
                        <div class="info-value" id="nic"></div>
                    </div>

                    <div class="info-card">
                        <div class="info-label">Birthday</div>
                        <div class="info-value" id="birthday"></div>
                    </div>

                    <div class="info-card">
                        <div class="info-label">Gender</div>
                        <div class="info-value" id="gender"></div>
                    </div>

                    <div class="info-card">
                        <div class="info-label">Address</div>
                        <div class="info-value" id="address"></div>
                    </div>

                    <div class="info-card">
                        <div class="info-label">Batch Year</div>
                        <div class="info-value" id="batch-year"></div>
                    </div>

                    <div class="info-card">
                        <div class="info-label">AL Batch Year</div>
                        <div class="info-value" id="al-batch-year"></div>
                    </div>

                    <div class="info-card">
                        <div class="info-label">Membership Year</div>
                        <div class="info-value" id="membership-year"></div>
                    </div>

                    <div class="info-card">
                        <div class="info-label">Cricket Years</div>
                        <div class="info-value" id="cricket-years"></div>
                    </div>

                    <div class="info-card">
                        <div class="info-label">Created At</div>
                        <div class="info-value" id="created-at"></div>
                    </div>

                </div>

                <div class="info-card">
                    <div class="info-label">
                        Annual Membership Fee
                    </div>

                    <div class="info-value" id="payment-status">
                        Checking...
                    </div>

                    <div id="payment-date"
                        style="margin-top:8px;font-size:12px;color:#666;">
                    </div>

                    <button
                        id="view-payment-receipt-btn"
                        class="btn btn-secondary"
                        style="
                            display:none;
                            margin-top:12px;
                            padding:8px 14px;
                            cursor:pointer;
                        ">
                        View Receipt
                    </button>
                </div> 

                <div class="info-card">
                        <div class="col-5">
                        <button type="button" class="btn btn-secondary w-100 py-2 mt-2" onclick="window.location.href='member-contributions-form.php?id=<?php echo htmlspecialchars($memberId); ?>'">
                        Contributions
                        </button>
                        </div>

                        <div class="col-5">
                        <button type="button" class="btn btn-secondary w-100 py-2 mt-2" onclick="window.location.href='member-edit-form.php?id=<?php echo htmlspecialchars($memberId); ?>'">
                        Edit
                        </button>
                        </div>               
                </div>


        </div>

    </div>
</div>
</div>

    <div id="loading" class="loading">
        Loading member profile...
    </div>

    <div id="error-container"></div>

    <div id="profile-content" style="display:none;">
        

            <!-- <div class="profile-photo">
                <img id="profile-photo"
                     src="https://via.placeholder.com/140"
                     alt="Profile Photo">
            </div> -->





        


<!-- Receipt Modal -->
<div id="receipt-modal"
     style="
        display:none;
        position:fixed;
        top:0;
        left:0;
        width:100%;
        height:100%;
        background:rgba(0,0,0,0.7);
        z-index:9999;
        justify-content:center;
        align-items:center;
     ">

    <div style="
        background:#fff;
        width:90%;
        max-width:900px;
        height:90%;
        border-radius:10px;
        overflow:hidden;
        position:relative;
    ">

        <button id="close-receipt-modal"
                style="
                    position:absolute;
                    right:10px;
                    top:10px;
                    z-index:10000;
                    padding:8px 12px;
                    cursor:pointer;
                ">
            Close
        </button>

      <!-- Loading -->
        <div id="receipt-loading"
             style="
                padding:20px;
                text-align:center;
             ">
            Loading receipt...
        </div>

        <!-- Error -->
        <div id="receipt-error"
             style="
                display:none;
                padding:20px;
                color:red;
                text-align:center;
             ">
        </div>

        <!-- Image -->
        <img id="receipt-image"
             src=""
             alt="Receipt"
             style="
                display:none;
                width:100%;
                height:100%;
                object-fit:contain;
             ">


        <iframe id="receipt-frame"
                src=""
                style="
                    width:100%;
                    height:100%;
                    border:none;
                ">
        </iframe>

    <!-- </div> -->
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>

const memberId = "<?php echo htmlspecialchars($memberId); ?>";

async function loadMemberProfile() {

    try {

        const response = await fetch(`/v1/member/${memberId}`);

        if (!response.ok) {
            throw new Error('Failed to load member');
        }

        const result = await response.json();

        if (!result.success) {
            throw new Error(result.message || 'Unknown error');
        }

        const data = result.data;

        document.getElementById('member-name').innerText =
            data.full_name || '-';

        document.getElementById('member-status').innerText =
            data.status || '-';

        document.getElementById('member-no').innerText =
            data.member_no || '-';

        document.getElementById('email').innerText =
            data.email || '-';

        document.getElementById('phone').innerText =
            data.phone || '-';

        document.getElementById('nic').innerText =
            data.nic || '-';

        document.getElementById('birthday').innerText =
            data.birthday || '-';

        document.getElementById('gender').innerText =
            data.gender || '-';

        document.getElementById('address').innerText =
            data.address || '-';

        document.getElementById('batch-year').innerText =
            data.batch_year || '-';

        document.getElementById('al-batch-year').innerText =
            data.al_batch_year || '-';

        document.getElementById('membership-year').innerText =
            data.membership_year || '-';

        document.getElementById('cricket-years').innerText =
            data.cricket_years || '-';

        document.getElementById('created-at').innerText =
            data.created_at || '-';

        loadPaymentStatus(memberId);

        if (data.profile_photo) {
            document.getElementById('profile-photo').src =
                data.profile_photo;
        }

        document.getElementById('loading').style.display = 'none';
        document.getElementById('profile-content').style.display = 'block';

    } catch (error) {

        document.getElementById('loading').style.display = 'none';

        document.getElementById('error-container').innerHTML = `
            <div class="error-box">
                ${error.message}
            </div>
        `;

        console.error(error);
    }
}


async function loadPaymentStatus(memberId)
{
    try {

        const response = await fetch(
            `/v1/member/${memberId}/payment-status`
        );

        if (!response.ok) {
            throw new Error('Unable to verify payment');
        }

        const result = await response.json();

        if (!result.success) {
            throw new Error(result.message || 'Payment check failed');
        }

        const payment = result.data;

        const paymentStatusElement =
            document.getElementById('payment-status');

        const paymentDateElement =
            document.getElementById('payment-date');

        if (payment.paid) {

            paymentStatusElement.innerHTML =
                'PAID';

            paymentDateElement.innerHTML =
                `Verified on: ${payment.payment_date}`;

            /*
            |--------------------------------------------------------------------------
            | Enable Receipt View Button
            |--------------------------------------------------------------------------
            */
            const viewBtn =
                document.getElementById('view-payment-receipt-btn');

            if (
                payment.payment_record &&
                payment.payment_record.file_path
            ) {

                viewBtn.style.display = 'inline-block';

                viewBtn.onclick = function () {
                loadReceiptImage(memberId);
                };
            }


        } else {

            paymentStatusElement.innerHTML =
                'NOT PAID';

            paymentDateElement.innerHTML =
                `No payment record for ${payment.year}`;
        }

    } catch (error) {

        console.error(error);

        document.getElementById('payment-status').innerHTML =
            'Unable to verify';

        document.getElementById('payment-date').innerHTML =
            error.message;
    }
}

loadMemberProfile();

/*
|--------------------------------------------------------------------------
| Load Receipt Image
|--------------------------------------------------------------------------
*/
async function loadReceiptImage(memberId)
{
    const modal =
        document.getElementById('receipt-modal');

    const image =
        document.getElementById('receipt-image');

    const loading =
        document.getElementById('receipt-loading');

    const errorBox =
        document.getElementById('receipt-error');

    /*
    |--------------------------------------------------------------------------
    | Reset UI
    |--------------------------------------------------------------------------
    */
    modal.style.display = 'flex';

    image.style.display = 'none';
    image.src = '';

    errorBox.style.display = 'none';
    errorBox.innerHTML = '';

    loading.style.display = 'block';

    try {

        /*
        |--------------------------------------------------------------------------
        | Request Receipt File
        |--------------------------------------------------------------------------
        */
        const response = await fetch(
            `/v1/member/${memberId}/payment-receipt`
        );

        if (!response.ok) {
            throw new Error(
                'Receipt could not be loaded'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Convert Blob To Browser URL
        |--------------------------------------------------------------------------
        */
        const blob = await response.blob();

        const imageUrl =
            URL.createObjectURL(blob);

        /*
        |--------------------------------------------------------------------------
        | Show Image
        |--------------------------------------------------------------------------
        */
        image.src = imageUrl;

        image.onload = function () {

            loading.style.display = 'none';

            image.style.display = 'block';
        };

    } catch (error) {

        console.error(error);

        loading.style.display = 'none';

        errorBox.style.display = 'block';

        errorBox.innerHTML = error.message;
    }
}


function openReceiptModal(filePath)
{
    const modal =
        document.getElementById('receipt-modal');

    const frame =
        document.getElementById('receipt-frame');

    frame.src = filePath;

    modal.style.display = 'flex';
}

document
    .getElementById('receipt-modal')
    .addEventListener('click', function (event) {

        if (event.target.id === 'receipt-modal') {

            closeReceiptModal();
        }
    });


document
    .getElementById('close-receipt-modal')
    .addEventListener('click', function () {

        closeReceiptModal();
    });    

function closeReceiptModal()
{
    const modal =
        document.getElementById('receipt-modal');

    const image =
        document.getElementById('receipt-image');

    modal.style.display = 'none';

    image.src = '';

    image.style.display = 'none';
}


/*
|--------------------------------------------------------------------------
| Close Modal On Background Click
|--------------------------------------------------------------------------
*/
document
    .getElementById('receipt-modal')
    .addEventListener('click', function (event) {

        if (event.target.id === 'receipt-modal') {

            document
                .getElementById('receipt-modal')
                .style.display = 'none';

            document
                .getElementById('receipt-frame')
                .src = '';
        }
    });


</script>

</body>
</html>