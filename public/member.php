<?php
$memberId = $_GET['member_id'] ?? '';

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
    </style>
</head>
<body>

<div class="profile-container">

    <div id="loading" class="loading">
        Loading member profile...
    </div>

    <div id="error-container"></div>

    <div id="profile-content" style="display:none;">
        <div class="profile-header">

            <div class="profile-photo">
                <img id="profile-photo"
                     src="https://via.placeholder.com/140"
                     alt="Profile Photo">
            </div>

            <div>
                <div class="member-name" id="member-name"></div>

                <div class="member-status" id="member-status"></div>

                <div style="margin-top:10px;">
                    Member No:
                    <strong id="member-no"></strong>
                </div>
            </div>
        </div>

        <div class="profile-grid">

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
    </div>
</div>

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

loadMemberProfile();

</script>

</body>
</html>