<?php

use yii\bootstrap5\ActiveForm;
use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Transaksi $model */

$form = ActiveForm::begin([
    'options' => ['enctype' => 'multipart/form-data'],
    'fieldConfig' => [
        'labelOptions' => ['class' => 'form-label fw-semibold small text-uppercase text-secondary ls-wider'],
    ],
]);
?>

<div class="card border shadow-sm">

    <div class="card-header bg-white border-bottom py-3 px-4 d-flex align-items-center gap-2">
        <div class="rounded-2 d-flex align-items-center justify-content-center bg-primary bg-opacity-10 text-primary"
            style="width:36px;height:36px;font-size:18px;">
            <i class="bi bi-receipt"></i>
        </div>
        <div>
            <p class="mb-0 fw-semibold"><?= $model->isNewRecord ? 'Tambah transaksi' : 'Edit transaksi' ?></p>
            <p class="mb-0 text-muted small">Isi semua field yang diperlukan</p>
        </div>
    </div>

    <div class="card-body px-4 py-4">
        <div class="row g-3">

            <!-- Nama -->
            <div class="col-12">
                <?= $form->field($model, 'name', [
                    'inputOptions' => ['class' => 'form-control', 'placeholder' => 'Contoh: Beli ATK kantor'],
                ])->textInput(['maxlength' => true])->label('Nama <span class="text-danger">*</span>', ['encode' => false]) ?>
            </div>

            <!-- Deskripsi -->
            <div class="col-12">
                <?= $form->field($model, 'description', [
                    'inputOptions' => ['class' => 'form-control'],
                ])->textarea(['rows' => 3, 'placeholder' => 'Keterangan tambahan (opsional)...']) ?>
            </div>

            <!-- Jumlah -->
            <div class="col-md-6">
                <?= $form->field($model, 'amount')->label('Jumlah <span class="text-danger">*</span>', ['encode' => false])
                    ->begin() ?>
                <div class="input-group">
                    <span class="input-group-text bg-light text-muted small fw-semibold">Rp</span>
                    <?= Html::activeInput('number', $model, 'amount', [
                        'class' => 'form-control',
                        'min'   => 0,
                        'placeholder' => '0',
                    ]) ?>
                </div>
                <?= $form->field($model, 'amount')->error() ?>
                <?= $form->field($model, 'amount')->end() ?>
                <div class="form-text">Masukkan nilai dalam rupiah</div>
            </div>

            <!-- Tanggal -->
            <div class="col-md-6">
                <?= $form->field($model, 'date')
                    ->label('Tanggal <span class="text-danger">*</span>', ['encode' => false])
                    ->input('datetime-local', ['class' => 'form-control']) ?>
            </div>

            <!-- Upload Foto -->
            <div class="col-12">
                <label class="form-label fw-semibold small text-uppercase text-secondary">
                    Foto
                </label>

                <div id="upload-zone" class="border rounded-2 p-4 text-center text-muted"
                    style="border-style:dashed!important;cursor:pointer;background:var(--bs-light,#f8f9fa);"
                    onclick="document.getElementById('foto-input').click()">
                    <i class="bi bi-cloud-upload fs-3 d-block mb-2"></i>
                    <p class="mb-1 small">Klik atau seret foto ke sini</p>
                    <p class="mb-0" style="font-size:11px;">JPG, PNG, WEBP, GIF — maks. 5 MB</p>
                </div>

                <?= $form->field($model, 'fotoFile', ['options' => ['class' => 'mb-0']])
                    ->fileInput([
                        'accept' => 'image/*',
                        'id'     => 'foto-input',
                        'class'  => 'd-none',
                    ])->label(false) ?>

                <div class="form-text">Biarkan kosong jika tidak ada foto</div>
            </div>

            <!-- Foto saat ini (edit mode) -->
            <?php if (!empty($model->foto)): ?>
            <div class="col-12" id="current-foto">
                <label class="form-label fw-semibold small text-uppercase text-secondary">
                    Foto saat ini
                </label>
                <div class="border rounded-2 overflow-hidden" style="max-width:360px;">
                    <?= Html::img($model->foto, [
                        'class' => 'd-block w-100',
                        'style' => 'max-height:200px;object-fit:cover;',
                        'alt'   => 'Foto transaksi',
                    ]) ?>
                    <p class="mb-0 px-3 py-2 text-muted small border-top" style="font-size:11px;">
                        Upload file baru untuk mengganti foto ini.
                    </p>
                </div>
            </div>
            <?php endif; ?>

            <!-- Preview foto baru -->
            <div class="col-12" id="foto-preview" style="display:none;">
                <label class="form-label fw-semibold small text-uppercase text-secondary">
                    Preview
                </label>
                <div class="border rounded-2 overflow-hidden position-relative" style="max-width:360px;">
                    <img id="preview-img" src="#" alt="Preview" class="d-block w-100"
                        style="max-height:200px;object-fit:cover;">
                    <span class="position-absolute top-0 end-0 m-2 badge"
                        style="background:var(--bs-success-bg-subtle);color:var(--bs-success-text-emphasis);font-size:11px;">
                        <i class="bi bi-check-lg me-1"></i>Dipilih
                    </span>
                    <button type="button" id="remove-foto"
                        class="btn btn-sm btn-link text-danger text-decoration-none px-3 py-2 border-top w-100 rounded-0"
                        style="font-size:12px;">
                        <i class="bi bi-x-circle me-1"></i>Hapus pilihan
                    </button>
                </div>
            </div>

        </div>
    </div>

    <div class="card-footer bg-white border-top px-4 py-3 d-flex gap-2">
        <?= Html::submitButton(
            '<i class="bi bi-floppy me-1"></i>' . ($model->isNewRecord ? 'Simpan' : 'Update'),
            ['class' => 'btn btn-primary', 'encode' => false]
        ) ?>
        <?= Html::a('<i class="bi bi-arrow-left me-1"></i>Batal', ['index'], [
            'class'  => 'btn btn-outline-secondary',
            'encode' => false,
        ]) ?>
    </div>

</div>

<?php ActiveForm::end(); ?>

<?php $this->registerJs(<<<JS
(function () {
    const input       = document.getElementById('foto-input');
    const zone        = document.getElementById('upload-zone');
    const preview     = document.getElementById('foto-preview');
    const previewImg  = document.getElementById('preview-img');
    const currentFoto = document.getElementById('current-foto');
    const removeBtn   = document.getElementById('remove-foto');

    function showPreview(file) {
        const reader = new FileReader();
        reader.onload = e => {
            previewImg.src = e.target.result;
            preview.style.display = 'block';
            if (currentFoto) currentFoto.style.display = 'none';
        };
        reader.readAsDataURL(file);
    }

    function clearPreview() {
        preview.style.display = 'none';
        previewImg.src = '#';
        input.value = '';
        if (currentFoto) currentFoto.style.display = 'block';
    }

    input.addEventListener('change', e => {
        if (e.target.files[0]) showPreview(e.target.files[0]);
        else clearPreview();
    });

    if (removeBtn) removeBtn.addEventListener('click', clearPreview);

    zone.addEventListener('dragover', e => { e.preventDefault(); zone.style.background = '#e9f2fc'; });
    zone.addEventListener('dragleave', () => { zone.style.background = ''; });
    zone.addEventListener('drop', e => {
        e.preventDefault();
        zone.style.background = '';
        const file = e.dataTransfer.files[0];
        if (!file) return;
        const dt = new DataTransfer();
        dt.items.add(file);
        input.files = dt.files;
        showPreview(file);
    });
})();
JS); ?>