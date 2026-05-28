<?php

/** @var yii\web\View $this */
/** @var app\models\Transaksi $model */

use yii\helpers\Html;

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Transaksi', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$fmt = fn(int $n) => 'Rp ' . number_format($n, 0, ',', '.');
$dateFormatted = $model->date
    ? Yii::$app->formatter->asDatetime($model->date, 'php:d M Y, H:i')
    : '—';
?>

<!-- Top bar -->
<div class="d-flex justify-content-between align-items-start mb-4 flex-wrap gap-2">
    <div>
        <h1 class="h4 fw-semibold mb-1">
            <?= Html::encode($model->name) ?>
            <span class="badge bg-secondary bg-opacity-10 text-secondary fw-normal ms-1"
                style="font-size:12px;vertical-align:middle;">#<?= $model->id ?></span>
        </h1>
        <p class="text-muted small mb-0">
            <i class="bi bi-clock me-1"></i>Ditambahkan <?= $dateFormatted ?>
        </p>
    </div>
    <div class="d-flex gap-2">
        <?= Html::a(
            '<i class="bi bi-arrow-left me-1"></i>Kembali',
            ['index'],
            ['class' => 'btn btn-outline-secondary btn-sm', 'encode' => false]
        ) ?>
        <?= Html::a(
            '<i class="bi bi-pencil me-1"></i>Edit',
            ['update', 'id' => $model->id],
            ['class' => 'btn btn-primary btn-sm', 'encode' => false]
        ) ?>
        <?= Html::a(
            '<i class="bi bi-trash me-1"></i>Hapus',
            ['delete', 'id' => $model->id],
            [
                'class'  => 'btn btn-outline-danger btn-sm',
                'encode' => false,
                'data'   => [
                    'confirm' => 'Yakin ingin menghapus transaksi ini?',
                    'method'  => 'post',
                ],
            ]
        ) ?>
    </div>
</div>

<!-- Info cards -->
<div class="row g-3 mb-3">

    <!-- Informasi umum -->
    <div class="col-md-6">
        <div class="card border shadow-sm h-100">
            <div class="card-header bg-white border-bottom py-2 px-3">
                <p class="mb-0 small fw-semibold text-uppercase text-secondary" style="letter-spacing:.05em;">
                    <i class="bi bi-info-circle me-1"></i>Informasi umum
                </p>
            </div>
            <div class="card-body px-3 py-2">
                <table class="table table-borderless mb-0 small align-middle">
                    <tr>
                        <td class="text-muted ps-0" style="width:40%;">
                            <i class="bi bi-tag me-1"></i>Nama
                        </td>
                        <td class="fw-semibold text-end pe-0">
                            <?= Html::encode($model->name) ?>
                        </td>
                    </tr>
                    <tr>
                        <td class="text-muted ps-0">
                            <i class="bi bi-calendar3 me-1"></i>Tanggal
                        </td>
                        <td class="text-end pe-0">
                            <?= $dateFormatted ?>
                        </td>
                    </tr>
                    <tr>
                        <td class="text-muted ps-0">
                            <i class="bi bi-hash me-1"></i>ID
                        </td>
                        <td class="text-end pe-0 text-muted">
                            #<?= $model->id ?>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    <!-- Biaya -->
    <div class="col-md-6">
        <div class="card border shadow-sm h-100">
            <div class="card-header bg-white border-bottom py-2 px-3">
                <p class="mb-0 small fw-semibold text-uppercase text-secondary" style="letter-spacing:.05em;">
                    <i class="bi bi-cash-coin me-1"></i>Biaya
                </p>
            </div>
            <div class="card-body px-3 d-flex align-items-center">
                <div>
                    <p class="text-muted small mb-1">Total jumlah transaksi</p>
                    <p class="fs-4 fw-semibold text-primary mb-0">
                        <?= $fmt($model->amount) ?>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Deskripsi -->
    <div class="col-12">
        <div class="card border shadow-sm">
            <div class="card-header bg-white border-bottom py-2 px-3">
                <p class="mb-0 small fw-semibold text-uppercase text-secondary" style="letter-spacing:.05em;">
                    <i class="bi bi-card-text me-1"></i>Deskripsi
                </p>
            </div>
            <div class="card-body px-3 py-3">
                <?php if (!empty($model->description)): ?>
                <p class="mb-0 small" style="line-height:1.7;">
                    <?= nl2br(Html::encode($model->description)) ?>
                </p>
                <?php else: ?>
                <p class="mb-0 text-muted small fst-italic">Tidak ada deskripsi.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Foto -->
    <div class="col-12">
        <div class="card border shadow-sm overflow-hidden">
            <div class="card-header bg-white border-bottom py-2 px-3">
                <p class="mb-0 small fw-semibold text-uppercase text-secondary" style="letter-spacing:.05em;">
                    <i class="bi bi-image me-1"></i>Foto transaksi
                </p>
            </div>
            <?php if (!empty($model->foto)): ?>
            <div class="position-relative">
                <?= Html::img($model->foto, [
                        'class' => 'd-block w-100',
                        'style' => 'max-height:320px;object-fit:cover;',
                        'alt'   => Html::encode($model->name),
                    ]) ?>
                <a href="<?= Html::encode($model->foto) ?>" target="_blank"
                    class="position-absolute bottom-0 end-0 m-2 btn btn-sm btn-light border" style="font-size:12px;">
                    <i class="bi bi-arrows-fullscreen me-1"></i>Lihat penuh
                </a>
            </div>
            <?php else: ?>
            <div class="card-body text-center text-muted py-5">
                <i class="bi bi-image fs-2 d-block mb-2 opacity-25"></i>
                <p class="small mb-0">Tidak ada foto untuk transaksi ini.</p>
            </div>
            <?php endif; ?>
        </div>
    </div>

</div>