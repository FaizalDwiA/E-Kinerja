<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Slip Gaji Karyawan</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        .salary-slip {
            width: 700px;
            border: 1px solid #000;
            padding: 20px;
            margin: 20px auto;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            background-color: #fff;
        }

        .header {
            padding-bottom: 20px;
            position: relative;
        }

        .header img {
            float: left;
            width: 100px;
            margin-right: 20px;
        }

        .header h2 {
            margin: 0;
        }

        .header .tgl-alamat {
            overflow: hidden;
            margin-top: 10px;
        }

        .header .tgl-alamat p {
            margin: 0;
        }

        .header .alamat {
            float: left;
            width: 70%;
        }

        .header #current-date {
            float: right;
            text-align: right;
            width: 30%;
        }

        .footer {
            text-align: center;
            margin-top: 30px;
        }

        .content {
            margin-top: 10px;
        }

        .content table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .content th,
        .content td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        .content .amount {
            text-align: right;
        }

        .content .highlight {
            background-color: #c9daf9;
            font-weight: bold;
        }

        .content .total-words {
            font-weight: bold;
            text-align: right;
            padding-right: 10px;
        }

        @media print {

            .content th,
            .content .highlight {
                background-color: #c9daf9 !important;
                -webkit-print-color-adjust: exact;
            }
        }
    </style>
</head>

<body>
    <div class="salary-slip">
        <div class="header">
            <img src="img/logo.png" alt="Logo">
            <h2>SLIP GAJI KARYAWAN</h2>
            <p class="nama">CV Berkah Solo Web</p>
            <div class="tgl-alamat">
                <p class="alamat">di Dukuh Pongan RT.04 RW.05, Desa Pondok, Kec. Grogol, Kab. Sukoharjo, Jawa Tengah</p>
                <p id="current-date"></p>
            </div>
        </div>

        <hr>

        <div class="content">
            <table>
                <tr>
                    <td>Nama</td>
                    <td>: <?= htmlspecialchars($model['user']['nama_lengkap']); ?></td>
                    <td>Alamat</td>
                    <td>: <?= htmlspecialchars($model['user']['alamat']); ?></td>
                </tr>
                <tr>
                    <td>Jabatan</td>
                    <td>: <?= htmlspecialchars($model['user']['jabatan']); ?></td>
                    <td>Telp.</td>
                    <td>: <?= htmlspecialchars($model['user']['wa']); ?></td>
                </tr>
            </table>

            <hr>

            <table>
                <thead class="highlight">
                    <tr>
                        <th>Keterangan</th>
                        <th class="amount">Jumlah</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Gaji Pokok</td>
                        <td class="amount"><?= number_format($model['data']['gaji_pokok'], 0, ",", "."); ?></td>
                    </tr>
                    <tr>
                        <td>Tunjangan</td>
                        <td class="amount"><?= number_format($model['data']['tunjangan'], 0, ",", "."); ?></td>
                    </tr>
                    <tr>
                        <td>Pemotongan</td>
                        <td class="amount"><?= number_format($model['data']['pemotongan'], 0, ",", "."); ?></td>
                    </tr>
                    <tr class="highlight">
                        <td>Total</td>
                        <td class="amount">Rp <?= number_format($model['data']['gaji_total'], 0, ",", "."); ?></td>
                    </tr>
                    <tr class="highlight">
                        <td colspan="2" class="total-words" id="total-in-words"></td>
                    </tr>
                </tbody>
            </table>
        </div>

        <br><br>

        <div class="footer">
            <p>Mengetahui</p><br><br>
            <p>Pemilik</p>
            <p>Aditya Wahyu Wijanarko</p>
        </div>
    </div>

    <script>
        window.addEventListener("load", function() {
            window.print();
        });

        const today = new Date();
        const options = {
            year: 'numeric',
            month: '2-digit',
            day: '2-digit'
        };
        document.getElementById('current-date').textContent = 'Tanggal: ' + today.toLocaleDateString('id-ID', options);

        function numberToWords(num) {
            const units = ['nol', 'satu', 'dua', 'tiga', 'empat', 'lima', 'enam', 'tujuh', 'delapan', 'sembilan'];
            const teens = ['sepuluh', 'sebelas', 'dua belas', 'tiga belas', 'empat belas', 'lima belas', 'enam belas', 'tujuh belas', 'delapan belas', 'sembilan belas'];
            const tens = ['dua puluh', 'tiga puluh', 'empat puluh', 'lima puluh', 'enam puluh', 'tujuh puluh', 'delapan puluh', 'sembilan puluh'];
            const thousands = ['', 'ribu', 'juta', 'miliar', 'triliun'];

            function getWords(n) {
                if (n === 0) return '';
                if (n < 10) return units[n];
                if (n < 20) return teens[n - 10];
                if (n < 100) return tens[Math.floor(n / 10) - 2] + (n % 10 === 0 ? '' : ' ' + units[n % 10]);
                if (n < 1000) return units[Math.floor(n / 100)] + ' ratus' + (n % 100 === 0 ? '' : ' ' + getWords(n % 100));
                for (let i = 0; i < thousands.length; i++) {
                    const unit = 1000 ** (i + 1);
                    if (n < unit * 1000) return getWords(Math.floor(n / unit)) + ' ' + thousands[i] + (n % unit === 0 ? '' : ' ' + getWords(n % unit));
                }
            }

            function parseNumber(num) {
                const words = [];
                let part = 0;
                let unitIndex = 0;

                while (num > 0) {
                    part = num % 1000;
                    if (part > 0) {
                        words.unshift(getWords(part) + ' ' + thousands[unitIndex]);
                    }
                    num = Math.floor(num / 1000);
                    unitIndex++;
                }

                return words.join(' ').trim();
            }

            return parseNumber(num);
        }

        const totalAmount = <?= (int) $model['data']['gaji_total']; ?>;
        const totalInWords = numberToWords(totalAmount);
        document.getElementById('total-in-words').textContent = `(${totalInWords} rupiah)`;
    </script>
</body>

</html>