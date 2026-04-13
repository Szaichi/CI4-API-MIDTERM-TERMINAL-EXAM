<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>


<div class="d-flex justify-content-center">
<div class="card p-4 shadow-sm" style="max-width:650px; width:100%; border-radius:18px;">

    <!-- HEADER -->
    <div class="position-relative mb-4">

        <h3 class="fw-bold text-center">Edit Profile</h3>

        <a href="/profile"
        class="btn btn-sm position-absolute"
        style="top:0; right:0; border:1px solid #f06292; color:#f06292;">
            Back
        </a>

    </div>

    <!-- ERRORS -->
<?php if(session()->getFlashdata('errors')): ?>
    <div class="alert alert-danger">
        <?php foreach(session()->getFlashdata('errors') as $error): ?>
            <div><?= esc($error) ?></div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<form action="/profile/update" method="post" enctype="multipart/form-data">

    <?= csrf_field() ?>

    <!-- IMAGE PREVIEW -->
    <div class="text-center mb-4">

        <div style="position:relative; display:inline-block;">

            <img
                id="preview"
                src="<?= !empty($user['student_profile_image']) 
                    ? base_url('uploads/profiles/'.$user['student_profile_image']) 
                    : '' ?>"
                class="rounded-circle shadow-sm"
                style="
                    width:130px;
                    height:130px;
                    object-fit:cover;
                    display: <?= !empty($user['student_profile_image']) ? 'block' : 'none' ?>;
                "
            >

            <button
                id="removeBtn"
                type="button"
                onclick="removeImage()"
                style="
                    position:absolute;
                    top:5px;
                    right:5px;
                    background:#ff4d4d;
                    color:white;
                    border:none;
                    width:28px;
                    height:28px;
                    border-radius:50%;
                    cursor:pointer;
                    font-weight:bold;
                    display: <?= !empty($user['student_profile_image']) ? 'block' : 'none' ?>;
                ">
                ×
            </button>

        </div>

        <input type="hidden" name="remove_image" id="removeImageInput" value="0">

        <input
            type="file"
            id="fileInput"
            name="profile_image"
            class="form-control mt-3"
            accept="image/*"
            onchange="previewImage(event)"
        >

    </div>

    <hr>

    <!-- FIELDS -->
    <div class="mb-3">
        <label class="form-label fw-semibold">Name</label>
        <input name="name" class="form-control"
        value="<?= old('name', $user['name'] ?? '') ?>" required>
    </div>

    <div class="mb-3">
        <label class="form-label fw-semibold">Email</label>
        <input name="email" type="email" class="form-control"
        value="<?= old('email', $user['email'] ?? '') ?>" required>
    </div>

    <div class="mb-3">
        <label class="form-label fw-semibold">Student ID</label>
        <input 
            class="form-control bg-light"
            value="<?= esc($user['student_student_id'] ?? '-') ?>"
            readonly
        >
    </div>

    <div class="mb-3">
        <label class="form-label fw-semibold">Course</label>
        <input name="course" class="form-control"
        value="<?= old('course', $user['course'] ?? '') ?>" required>
    </div>

    <div class="mb-3">
        <label class="form-label fw-semibold">Year Level</label>
        <input name="year_level" class="form-control"
        value="<?= old('year_level', $user['year_level'] ?? '') ?>" required>
    </div>

    <div class="mb-3">
        <label class="form-label fw-semibold">Section</label>
        <input name="section" class="form-control"
        value="<?= old('section', $user['section'] ?? '') ?>" required>
    </div>

    <div class="mb-3">
        <label class="form-label fw-semibold">Phone</label>
        <input name="phone" class="form-control"
        value="<?= old('student_phone', $user['student_phone'] ?? '') ?>" required>
    </div>

    <div class="mb-3">
        <label class="form-label fw-semibold">Address</label>
        <textarea name="address" class="form-control" rows="3" required><?= old('student_address', $user['student_address'] ?? '') ?></textarea>
    </div>

    <button class="btn w-100 mt-3"
        style="background:#f06292; color:white; border-radius:10px;">
        Update Profile
    </button>

</form>

</div>
</div>

<script>

// IMAGE //
function previewImage(event){

    const file = event.target.files[0];

    if(!file) return;

    const allowedTypes = [
        "image/jpeg",
        "image/jpg",
        "image/png",
        "image/webp"
    ];

    if(!allowedTypes.includes(file.type)){
        alert("Only JPG, JPEG, PNG, and WEBP images are allowed.");
        event.target.value = "";
        return;
    }

    const reader = new FileReader();

    reader.onload = function(){

        const preview = document.getElementById('preview');
        const removeBtn = document.getElementById('removeBtn');

        preview.src = reader.result;
        preview.style.display = "block";

        removeBtn.style.display = "block";

        document.getElementById('removeImageInput').value = 0;

    }

    reader.readAsDataURL(file);

}

function removeImage(){

    const preview = document.getElementById('preview');
    const removeBtn = document.getElementById('removeBtn');
    const fileInput = document.getElementById('fileInput');

    preview.src = "";
    preview.style.display = "none";

    removeBtn.style.display = "none";

    fileInput.value = "";

    document.getElementById('removeImageInput').value = 1;

}

</script>

<?= $this->endSection() ?>