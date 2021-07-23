-- phpMyAdmin SQL Dump
-- version 5.1.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 23 Jul 2021 pada 16.28
-- Versi server: 10.4.18-MariaDB
-- Versi PHP: 7.3.27

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `inventory_bangunan`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `inventory_bangunan`
--

CREATE TABLE `inventory_bangunan` (
  `id_inventory_bangunan` int(11) NOT NULL,
  `tanggal` date NOT NULL,
  `id_nama_material` int(11) NOT NULL,
  `id_merk_material` int(11) NOT NULL,
  `id_supplier` int(11) NOT NULL,
  `id_uom` int(11) NOT NULL,
  `jumlah` int(11) NOT NULL,
  `harga` int(11) NOT NULL,
  `total` int(11) NOT NULL,
  `keterangan` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `inventory_keluar`
--

CREATE TABLE `inventory_keluar` (
  `id_inventory_stok` int(11) NOT NULL,
  `tanggal` date NOT NULL,
  `id_nama_material` int(11) NOT NULL,
  `jumlah` int(11) NOT NULL,
  `id_inventory_bangunan` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Struktur dari tabel `inventory_stok`
--

CREATE TABLE `inventory_stok` (
  `id_inventory_stok` int(11) NOT NULL,
  `id_nama_material` int(11) NOT NULL,
  `stok` int(11) NOT NULL,
  `id_inventory_bangunan` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Struktur dari tabel `merk_material`
--

CREATE TABLE `merk_material` (
  `id_merk_material` int(11) NOT NULL,
  `merk_material` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `nama_material`
--

CREATE TABLE `nama_material` (
  `id_nama_material` int(11) NOT NULL,
  `nama_material` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `supplier`
--

CREATE TABLE `supplier` (
  `id_supplier` int(11) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `alamat` varchar(100) NOT NULL,
  `kota` varchar(50) NOT NULL,
  `telepon` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `uom`
--

CREATE TABLE `uom` (
  `id_uom` int(11) NOT NULL,
  `uom` varchar(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id_user` int(11) NOT NULL,
  `user_name` varchar(100) NOT NULL,
  `user_email` varchar(100) NOT NULL,
  `user_phone` char(16) DEFAULT NULL,
  `user_address` text DEFAULT NULL,
  `user_avatar` varchar(255) DEFAULT 'default.jpg',
  `user_password` varchar(255) NOT NULL,
  `user_role` enum('admin','staff') DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `inventory_bangunan`
--
ALTER TABLE `inventory_bangunan`
  ADD PRIMARY KEY (`id_inventory_bangunan`);

--
-- Indeks untuk tabel `inventory_keluar`
--
ALTER TABLE `inventory_keluar`
  ADD PRIMARY KEY (`id_inventory_stok`),
  ADD KEY `id_spare_part` (`id_nama_material`);

--
-- Indeks untuk tabel `inventory_stok`
--
ALTER TABLE `inventory_stok`
  ADD PRIMARY KEY (`id_inventory_stok`),
  ADD KEY `id_spare_part` (`id_nama_material`);

--
-- Indeks untuk tabel `merk_material`
--
ALTER TABLE `merk_material`
  ADD PRIMARY KEY (`id_merk_material`);

--
-- Indeks untuk tabel `nama_material`
--
ALTER TABLE `nama_material`
  ADD PRIMARY KEY (`id_nama_material`);

--
-- Indeks untuk tabel `supplier`
--
ALTER TABLE `supplier`
  ADD PRIMARY KEY (`id_supplier`);

--
-- Indeks untuk tabel `uom`
--
ALTER TABLE `uom`
  ADD PRIMARY KEY (`id_uom`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id_user`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `inventory_bangunan`
--
ALTER TABLE `inventory_bangunan`
  MODIFY `id_inventory_bangunan` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `inventory_keluar`
--
ALTER TABLE `inventory_keluar`
  MODIFY `id_inventory_stok` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `inventory_stok`
--
ALTER TABLE `inventory_stok`
  MODIFY `id_inventory_stok` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `merk_material`
--
ALTER TABLE `merk_material`
  MODIFY `id_merk_material` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `nama_material`
--
ALTER TABLE `nama_material`
  MODIFY `id_nama_material` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `supplier`
--
ALTER TABLE `supplier`
  MODIFY `id_supplier` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `uom`
--
ALTER TABLE `uom`
  MODIFY `id_uom` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
