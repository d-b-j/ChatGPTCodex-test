<?php
$success=''; $error=''; $qr='';
function clean($v){ return htmlspecialchars(trim($v ?? ''), ENT_QUOTES, 'UTF-8'); }
if($_SERVER['REQUEST_METHOD']==='POST'){
  $first_name=clean($_POST['first_name']);
  $last_name=clean($_POST['last_name']);
  $full_name=clean($_POST['full_name']);
  $email=clean($_POST['email']);
  $phone=clean($_POST['phone']);
  $batch_year=(int)($_POST['batch_year'] ?? 0);
  $gender=clean($_POST['gender']);
  $status=clean($_POST['status']);

  if(!$first_name || !$last_name || !$full_name){ $error='Name fields are required.'; }
  elseif(!filter_var($email, FILTER_VALIDATE_EMAIL)){ $error='Invalid email address.'; }
  elseif(!preg_match('/^\+?[0-9]{7,15}$/',$phone)){ $error='Phone must contain 7 to 15 digits.'; }
  elseif($batch_year < 1950 || $batch_year > (int)date('Y')+1){ $error='Invalid batch year.'; }
  elseif(!in_array($gender,['male','female','other'])){ $error='Invalid gender.'; }
  elseif(!in_array($status,['active','inactive'])){ $error='Invalid status.'; }
  else {
    $payload=json_encode([
      'first_name'=>$first_name,
      'last_name'=>$last_name,
      'full_name'=>$full_name,
      'email'=>$email,
      'phone'=>$phone,
      'batch_year'=>$batch_year,
      'gender'=>$gender,
      'status'=>$status
    ]);

    $ch=curl_init('https://qrrest.technolide.xyz/v1/member');
    curl_setopt_array($ch,[CURLOPT_RETURNTRANSFER=>true,CURLOPT_POST=>true,CURLOPT_HTTPHEADER=>['Content-Type: application/json'],CURLOPT_POSTFIELDS=>$payload]);
    $response=curl_exec($ch);
    $http=curl_getinfo($ch,CURLINFO_HTTP_CODE);
    $cerr=curl_error($ch);
    curl_close($ch);

    if($cerr){ $error='cURL Error: '.$cerr; }
    elseif($http>=200 && $http<300){
      $success='Member submitted successfully.';
      $qrData=$full_name.' | '.$email.' | '.$phone;
      $qr='https://api.qrserver.com/v1/create-qr-code/?size=220x220&data='.urlencode($qrData);
    } else {
      $error='API Error: '.$response;
    }
  }
}
?>
<!DOCTYPE html>
<html lang='en'>
<head>
<meta charset='UTF-8'>
<meta name='viewport' content='width=device-width, initial-scale=1'>
<title>QR Member Form</title>
<link href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css' rel='stylesheet'>
</head>
<body class='bg-light'>
<div class='container py-5'>
<div class='row justify-content-center'>
<div class='col-lg-8'>
<div class='card shadow-lg border-0 rounded-4'>
<div class='card-body p-4'>
<h2 class='mb-4'>QR Member Registration</h2>
<?php if($success): ?><div class='alert alert-success'><?php echo $success; ?></div><?php endif; ?>
<?php if($error): ?><div class='alert alert-danger'><?php echo $error; ?></div><?php endif; ?>
<form method='post' novalidate>
<div class='row g-3'>
<div class='col-md-6'><label class='form-label'>First Name</label><input name='first_name' class='form-control' required value='JohnI'></div>
<div class='col-md-6'><label class='form-label'>Last Name</label><input name='last_name' class='form-control' required value='DoeII'></div>
<div class='col-12'><label class='form-label'>Full Name</label><input name='full_name' class='form-control' required value='JohnI DoeII'></div>
<div class='col-md-6'><label class='form-label'>Email</label><input type='email' name='email' class='form-control' required value='johnii@example.com'></div>
<div class='col-md-6'><label class='form-label'>Phone</label><input name='phone' class='form-control' pattern='^\+?[0-9]{7,15}$' required value='+1234567892'></div>
<div class='col-md-4'><label class='form-label'>Batch Year</label><input type='number' min='1950' max='2100' name='batch_year' class='form-control' required value='2020'></div>
<div class='col-md-4'><label class='form-label'>Gender</label><select name='gender' class='form-select'><option value='male'>Male</option><option value='female'>Female</option><option value='other'>Other</option></select></div>
<div class='col-md-4'><label class='form-label'>Status</label><select name='status' class='form-select'><option value='active'>Active</option><option value='inactive'>Inactive</option></select></div>
<div class='col-12'><button class='btn btn-primary w-100 py-2'>Submit Member</button></div>
</div>
</form>
<?php if($qr): ?>
<hr class='my-4'>
<h5>Generated QR Code</h5>
<img src='<?php echo $qr; ?>' class='img-thumbnail p-2' alt='QR Code'>
<p class='text-muted mt-2 mb-0'>Scan to view stored member details text.</p>
<?php endif; ?>
</div></div></div></div></div>
</body></html>
