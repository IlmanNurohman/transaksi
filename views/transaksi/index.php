<?php
/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */
/** @var int $totalAmount */
/** @var int $totalCount */
/** @var int $monthlyCount */
/** @var int $monthlyAmount */
/** @var int $averageAmount */

use yii\grid\GridView;
use yii\helpers\Html;

$this->title = 'Transaksi';
$this->params['breadcrumbs'][] = $this->title;

$fmt = fn(int $n) => 'Rp ' . number_format($n, 0, ',', '.');
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <?= Html::a('<i class="bi bi-plus-lg me-1"></i>Tambah Transaksi', ['create'], ['class' => 'btn btn-primary']) ?>
</div>

<!-- Summary Cards -->
<div class="row g-3 mb-4">
    <div class="col-6 col-md-4">
        <div class="card border-0 bg-light h-100">
            <div class="card-body">
                <p class="text-muted small text-uppercase fw-semibold mb-1" style="letter-spacing:.05em">
                    <i class="bi bi-receipt me-1 text-primary"></i>Total Transaksi
                </p>
                <p class="fs-4 fw-semibold mb-0"><?= $totalCount ?></p>
                <p class="text-muted small mb-0">Semua waktu</p>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-4">
        <div class="card border-0 bg-light h-100">
            <div class="card-body">
                <p class="text-muted small text-uppercase fw-semibold mb-1" style="letter-spacing:.05em">
                    <i class="bi bi-cash-coin me-1 text-success"></i>Total Biaya
                </p>
                <p class="fs-4 fw-semibold mb-0"><?= $fmt($totalAmount) ?></p>
                <p class="text-muted small mb-0">Semua transaksi</p>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-4">
        <div class="card border-0 bg-light h-100">
            <div class="card-body">
                <p class="text-muted small text-uppercase fw-semibold mb-1" style="letter-spacing:.05em">
                    <i class="bi bi-graph-up me-1 text-info"></i>Rata-rata
                </p>
                <p class="fs-4 fw-semibold mb-0"><?= $fmt($averageAmount) ?></p>
                <p class="text-muted small mb-0">Per transaksi</p>
            </div>
        </div>
    </div>
</div>

<!-- Table -->
<div class="card border shadow-sm">
    <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
        <span class="fw-semibold">Daftar Transaksi</span>
    </div>
    <div class="card-body p-0">
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'summary'      => '<div class="px-3 py-2 text-muted small border-bottom">Menampilkan {begin}–{end} dari {totalCount} transaksi</div>',
            'tableOptions' => ['class' => 'table table-hover align-middle mb-0'],
            'layout'       => '{summary}{items}{pager}',
            'pager'        => ['class' => 'yii\bootstrap5\LinkPager', 'options' => ['class' => 'pagination pagination-sm px-3 py-2 mb-0']],
            'columns'      => [
              
                'name:text:Nama',
                'description:text:Deskripsi',
                [
                    'attribute' => 'amount',
                    'label'     => 'Jumlah',
                    'value'     => fn($m) => $fmt($m->amount),
                    'contentOptions' => ['class' => 'fw-semibold text-primary'],
                ],
                [
                    'attribute' => 'date',
                    'label'     => 'Tanggal',
                    'format'    => ['datetime', 'php:d M Y H:i'],
                ],
                [
                    'attribute' => 'foto',
                    'label'     => 'Foto',
                    'format'    => 'raw',
                    'value'     => fn($m) => $m->foto
                        ? '<span class="badge bg-primary bg-opacity-10 text-primary"><i class="bi bi-image me-1"></i>Ada</span>'
                        : '<span class="badge bg-secondary bg-opacity-10 text-secondary">–</span>',
                ],
                [
                    'class'    => 'yii\grid\ActionColumn',
                    'template' => '{view} {update} {delete}',
                    'buttons'  => [
                        'view'   => fn($url) => Html::a('<i class="bi bi-eye"></i>', $url, ['class' => 'btn btn-sm btn-outline-secondary me-1', 'title' => 'Lihat']),
                        'update' => fn($url) => Html::a('<i class="bi bi-pencil"></i>', $url, ['class' => 'btn btn-sm btn-outline-primary me-1', 'title' => 'Edit']),
                        'delete' => fn($url) => Html::a('<i class="bi bi-trash"></i>', $url, [
                            'class' => 'btn btn-sm btn-outline-danger',
                            'title' => 'Hapus',
                            'data'  => ['confirm' => 'Yakin hapus transaksi ini?', 'method' => 'post'],
                        ]),
                    ],
                ],
            ],
        ]) ?>
    </div>
</div>