<?php
session_start();
// Tampilkan alert jika session login_success ada
if (isset($_SESSION['login_success'])) {
    $loginSuccess = $_SESSION['login_success'];
    unset($_SESSION['login_success']); // Hapus session setelah menampilkan alert
}
// Tampilkan alert jika session logout_success ada
if (isset($_SESSION['logout_success'])) {
    $logoutSuccess = $_SESSION['logout_success'];
    unset($_SESSION['logout_success']); // Hapus session setelah menampilkan alert
}
// Tampilkan alert jika session delete_success ada
if (isset($_SESSION['delete_success'])) {
    $deleteSuccess = $_SESSION['delete_success'];
    unset($_SESSION['delete_success']); // Hapus session setelah menampilkan alert
}
if (isset($deleteSuccess)) : ?>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            Swal.fire({
                icon: '<?php echo $deleteSuccess ? 'success' : 'error'; ?>',
                title: '<?php echo $deleteSuccess ? 'Terhapus!' : 'Gagal!'; ?>',
                text: '<?php echo $deleteSuccess ? 'Barang berhasil dihapus dari stok.' : 'Terjadi kesalahan saat menghapus barang.'; ?>',
                showConfirmButton: true,
                confirmButtonText: 'Tutup',
                customClass: {
                    popup: 'animate__animated animate__fadeInDown'
                }
            });
        });
    </script>
<?php unset($deleteSuccess);
endif;
require 'function.php';
require 'cek.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <link rel="shortcut icon" href="image/icon-logo-bakti.png">
    <title>BAKTI Kominfo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
    <link href="css/styles.css" rel="stylesheet" />
    <!-- Link CDN SweetAlert2 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    <style>
        .zoomable {
            width: 100px;
        }

        .zoomable:hover {
            transform: scale(2);
            transition: 0.3s ease;
        }
    </style>

    <style>
        /* Custom alert animation */
        .alert-custom {
            opacity: 0;
            transform: translateY(-20px);
            animation: slideIn 0.6s ease-out forwards;
        }

        @keyframes slideIn {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>

<body class="sb-nav-fixed">
    <!-- SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- Script untuk Alert -->
    <script>
        // Cek variabel PHP apakah login berhasil dan tampilkan alert
        <?php if (isset($loginSuccess) && $loginSuccess): ?>
            Swal.fire({
                icon: 'success',
                title: 'Login Berhasil!',
                text: 'Selamat datang kembali!',
                showConfirmButton: false,
                timer: 2000
            });
        <?php endif; ?>
    </script>

    <nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
        <!-- Navbar Brand-->
        <a class="navbar-brand ps-3" href="index.php">
            <div class="logo">
                <img src="image/logobakti.png" height="65px">
            </div>
        </a>
        <!-- Sidebar Toggle-->
        <button class="btn btn-link btn-sm order-1 order-lg-0 me-4 me-lg-0" id="sidebarToggle"><i class="fas fa-bars"></i></button>
    </nav>
    <div id="layoutSidenav">
        <div id="layoutSidenav_nav">
            <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
                <div class="sb-sidenav-menu">
                    <div class="nav">
                        <a class="nav-link active" href="index.php">
                            <div class="sb-nav-link-icon"><i class="fas fa-boxes-stacked"></i></div>
                            Stock Barang
                        </a>
                        <a class="nav-link" href="masuk.php">
                            <div class="sb-nav-link-icon"><i class="fas fa-truck-moving"></i></div>
                            Barang Masuk
                        </a>
                        <a class="nav-link" href="keluar.php">
                            <div class="sb-nav-link-icon"><i class="fas fa-truck-ramp-box"></i></div>
                            Transaksi Barang Keluar
                        </a>
                        <a class="nav-link" href="history.php">
                            <div class="sb-nav-link-icon"><i class="fa-solid fa-clock-rotate-left"></i></div>
                            Riwayat Masuk
                        </a>
                        <a class="nav-link" href="historykeluar.php">
                            <div class="sb-nav-link-icon"><i class="fa-solid fa-outdent"></i></div>
                            Riwayat Keluar
                        </a>
                        <a class="nav-link" href="#" onclick="confirmLogout()">
                            <div class="sb-nav-link-icon"><i class="fas fa-arrow-right-from-bracket"></i></div>
                            Logout
                        </a>
                    </div>
                </div>
            </nav>
        </div>
        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid px-4">
                    <h1 class="mt-5">Stock Barang</h1>
                    <div class="card mb-4">
                        <div class="card-header">
                            <form method="post">
                                <div class="row mt-3">
                                    <div class="col">
                                        <h5>Pilih Tanggal</h5>
                                        <input type="date" name="tgl_mulai" class="" placeholder="From">
                                        <input type="date" name="tgl_selesai" class="ml-3" placeholder="To">
                                        <button type="submit" name="filter_tgl" class="btn btn-info ml-3">Filter</button>
                                    </div>
                                </div>
                            </form>
                            <form method="get">
                                <h5>Pilih Kategori</h5>
                                <select name="kategori">
                                    <option value="">Pilih:</option>
                                    <?php
                                    // Fetch all categories from the 'master_kategori' table
                                    $ambilsemuadatakategori = mysqli_query($conn, "SELECT * FROM master_kategori");
                                    while ($fetchkategori = mysqli_fetch_array($ambilsemuadatakategori)) {
                                        $namakategori = $fetchkategori['kategori'];
                                        $idkategori = $fetchkategori['id_kategori'];
                                        // Check if this category is the currently selected one
                                        $selected = (isset($_GET['kategori']) && $_GET['kategori'] == $idkategori) ? "selected" : "";
                                        echo "<option value='$idkategori' $selected>$namakategori</option>";
                                    }
                                    ?>
                                </select>
                                <input type="submit" class="btn btn-secondary" value="Submit">
                            </form>

                            <?php
                            // Inisialisasi variabel filter
                            $where_clause = array();  // Array untuk menyimpan kondisi WHERE
                            $query = "SELECT * FROM barang"; // Query dasar

                            // Filter berdasarkan tanggal jika filter_tgl dikirim
                            if (isset($_POST['filter_tgl'])) {
                                $tgl_mulai = $_POST['tgl_mulai'];
                                $tgl_selesai = $_POST['tgl_selesai'];

                                // Tambahkan kondisi untuk rentang tanggal jika tanggal mulai dan selesai diisi
                                if (!empty($tgl_mulai) && !empty($tgl_selesai)) {
                                    $where_clause[] = "tanggal BETWEEN '$tgl_mulai' AND '$tgl_selesai'";
                                }
                            }

                            // Filter berdasarkan kategori jika kategori dikirim
                            if (isset($_GET['kategori']) && !empty($_GET['kategori'])) {
                                $kategori = $_GET['kategori'];

                                // Tambahkan kondisi untuk kategori jika dipilih
                                $where_clause[] = "id_kategori = '$kategori'";
                            }

                            // Jika ada kondisi WHERE, gabungkan ke dalam query
                            if (count($where_clause) > 0) {
                                $query .= " WHERE " . implode(' AND ', $where_clause);
                            }

                            // Tambahkan ORDER BY jika diperlukan
                            $query .= " ORDER BY idbarang ASC";

                            // Eksekusi query
                            $ambilsemuadatabarang = mysqli_query($conn, $query);
                            ?>
                        </div>
                        <div class="card-body">
                            <table id="datatablesSimple">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Gambar</th>
                                        <th>Nama Barang</th>
                                        <th>Stock Awal</th>
                                        <th>Barang Masuk</th>
                                        <th>Stock Barang</th>
                                        <th>Barang Keluar</th>
                                        <th>Stock Akhir</th>
                                        <th>Tanggal</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $i = 1;
                                    while ($data = mysqli_fetch_array($ambilsemuadatabarang)) {
                                        $namabarang = $data['namabarang'];
                                        $gambar = $data['gambar'];
                                        $img = ($gambar == null) ? 'No Photo' : '<img src="assets/img/' . $gambar . '" class="zoomable">';
                                        $idb = $data['idbarang'];
                                        $stockawal = $data['stockawal'];
                                        $barangmasuk = $data['barangmasuk'];
                                        $stock = $data['stock'];
                                        $barangkeluar = $data['barangkeluar'];
                                        $stockakhir = $data['stockakhir'];
                                        $tanggal = $data['tanggal'];
                                    ?>
                                        <tr>
                                            <td><?= $i++; ?></td>
                                            <td><?= $img; ?></td>
                                            <td><?= $namabarang; ?></td>
                                            <td><?= $stockawal; ?></td>
                                            <td><?= $barangmasuk; ?></td>
                                            <td><?= $stock; ?></td>
                                            <td><?= $barangkeluar; ?></td>
                                            <td><?= $stockakhir; ?></td>
                                            <td><?= $tanggal; ?></td>
                                            <td>
                                                <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#delete<?= $idb; ?>">
                                                    <i class="fas fa-trash me-2"></i>Delete
                                                </button>
                                            </td>
                                        </tr>
                                        <!-- Delete Modal -->
                                        <div class="modal fade" id="delete<?= $idb; ?>">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <!-- Modal Header -->
                                                    <div class="modal-header">
                                                        <h4 class="modal-title">Hapus Barang</h4>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <!-- Modal body -->
                                                    <form method="post">
                                                        <div class="modal-body">
                                                            Apakah Anda Yakin Ingin Menghapus <?= $namabarang; ?>?
                                                            <br>
                                                            <br>
                                                            <input type="hidden" name="idb" value="<?= $idb; ?>">
                                                            <button type="submit" class="btn btn-danger" name="hapusbarang"><i class="fas fa-trash me-2"></i>Delete</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    <?php
                                    }
                                    ?>
                                </tbody>
                            </table>

                            <!-- Button to Open the Modal -->
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#myModal">
                                Tambah Barang
                            </button>
                            <a href="export.php" class="btn btn-success">Export Data</a>
                        </div>
                    </div>
                </div>
            </main>
            <footer class="py-4 bg-light mt-auto">
                <div class="container-fluid px-4">
                    <div class="d-flex align-items-center justify-content-between small">
                        <div class="text-muted">Copyright &copy; BAKTI Kominfo 2024</div>
                    </div>
                </div>
            </footer>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script src="js/scripts.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script>
    <script src="assets/demo/chart-area-demo.js"></script>
    <script src="assets/demo/chart-bar-demo.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js" crossorigin="anonymous"></script>
    <script src="js/datatables-simple-demo.js"></script>
    <script>
        function confirmLogout() {
            Swal.fire({
                title: 'Konfirmasi Logout',
                text: "Apakah Anda yakin ingin logout?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Logout!'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = 'logout.php'; // Redirect ke logout.php
                }
            });
        }
    </script>
</body>
<!-- The Modal -->
<div class="modal fade" id="myModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <h3 class="modal-title">Tambah Barang</h3>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <!-- Modal body -->
            <form method="post" enctype="multipart/form-data">
                <div class="modal-body">
                    <h5><label for="gambar" class="control-laber ">Gambar Barang</label></h5>
                    <input type="file" name="gambar" class="form-control" required>
                    <br>
                    <select name="kategorinya" class="form-control">
                        <?php
                        $ambilsemuadatabarang = mysqli_query($conn, "SELECT * FROM master_kategori");
                        while ($data = mysqli_fetch_array($ambilsemuadatabarang)) {
                        ?>
                            <option value="<?= $data['id_kategori'] ?>"><?= $data['kategori'] ?></option>
                        <?php
                        }
                        ?>
                    </select>
                    <br>
                    <input type="text" name="namabarang" placeholder="Nama Barang" class="form-control" required>
                    <br>
                    <input type="number" name="stockawal" placeholder="Stock Awal" class="form-control" required>
                    <br>
                    <input type="date" name="tanggal" placeholder="Tanggal" class="form-control" required>
                    <br>
                    <button type="submit" class="btn btn-primary" name="addnewbarang">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>

</html>