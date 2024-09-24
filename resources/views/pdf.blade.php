<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Laporan Barang</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }

        .header-table {
            width: 100%;
            border-collapse: collapse;
            border: none;
        }

        .header-table td {
            vertical-align: top;
            border: none;
        }

        .header-table img {
            width: 100px;
            height: auto;
        }

        .header-table .text-content {
            padding-left: 20px;
        }

        .header-table .text-content h1 {
            margin: 5px 0;
        }

        .address {
            margin-top: 10px;
        }

        .report-number {
            margin-top: 50px;
            margin-bottom: 50px;
            font-weight: bold;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }

        table,
        th,
        td {
            border: 1px solid black;
        }

        th,
        td {
            padding: 10px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        .table-title {
            margin-bottom: 10px;
            font-weight: normal;
        }

        .thick-hr {
            border: 0;
            height: 3px;
            background: #000;
            margin-bottom: 20px;
        }

        .section-divider {
            border: 0;
            height: 3px;
            background: gray;
            margin: 20px 0;
        }

        .chart-container {
            width: 100%;
            margin: 20px 0;
        }
    </style>

</head>

<body>
    <table class="header-table">
        <tr>
            <td>
                <img src="{{ public_path('img/MS.png') }}" alt="Logo Perusahaan" style="width: 180px; height: auto;">
            </td>
            <td class="text-content">
                <h1>{{ $title }}</h1>
                <div class="address">
                    Jl. Komp. Mutiara, Telaga Biru, Kec. Banjarmasin Bar., Kota Banjarmasin, Kalimantan Selatan 70117
                </div>
            </td>
        </tr>
    </table>

    <hr class="thick-hr">

    <div class="data-tables">
        @foreach ($data as $index => $dataTable)
            @php
                $startDate = $dataTable['start_date'];
                $endDate = $dataTable['end_date'];
                $items = $dataTable['data'];
                $index++;
                // Initialize totals array
                $totals = [];

                // Aggregate totals by kode_barang
                foreach ($items as $item) {
                    if (!isset($totals[$item->kode_barang])) {
                        $totals[$item->kode_barang] = [
                            'kode_barang' => $item->kode_barang,
                            'nama_barang' => $item->nama_barang,
                            'total_in' => 0,
                            'total_out' => 0,
                            'code' => $item->code,
                        ];
                    }
                    $totals[$item->kode_barang]['total_in'] += $item->total_in;
                    $totals[$item->kode_barang]['total_out'] += $item->total_out;
                }

                // Prepare data for chart
                $chartData = [
                    'labels' => [],
                    'dataIn' => [],
                    'dataOut' => [],
                ];

                foreach ($totals as $totalItem) {
                    $chartData['labels'][] = $totalItem['nama_barang'];
                    $chartData['dataIn'][] = $totalItem['total_in'];
                    $chartData['dataOut'][] = $totalItem['total_out'];
                }
            @endphp
            <div>
                <div class="table-section">
                    <div class="table-title">
                        @if ($startDate != $endDate)
                            Data barang masuk dan keluar dari tanggal <b>{{ $startDate }}</b> sampai dengan
                            <b>{{ $endDate }}</b>:
                        @else
                            Data barang masuk dan pada keluar tanggal <b>{{ $startDate }}</b>:
                        @endif
                    </div>
                    <table>
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Kode Barang</th>
                                <th>Nama Barang</th>
                                <th>Masuk</th>
                                <th>Keluar</th>
                                <th>Tanggal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $no = 1;
                            @endphp
                            @foreach ($items as $item)
                                <tr>
                                    <td>{{ $no++ }}</td>
                                    <td>{{ $item->kode_barang }}</td>
                                    <td>{{ $item->nama_barang }}</td>
                                    <td>{{ $item->total_in }} {{ $item->code }}</td>
                                    <td>{{ $item->total_out }} {{ $item->code }}</td>
                                    <td>{{ $item->tanggal }} </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- TABLE RINGKASAN, GABUNGAN PER ITEM --}}
                <div class="table-section">
                    <div class="table-title">
                        Ringkasan:
                    </div>
                    <table>
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Kode Barang</th>
                                <th>Nama Barang</th>
                                <th>Masuk</th>
                                <th>Keluar</th>
                            </tr>
                        </thead>
                        @php
                            $noSUM = 1;
                        @endphp
                        <tbody>
                            @foreach ($totals as $totalItem)
                                <tr>
                                    <td>{{ $noSUM++ }}</td>
                                    <td>{{ $totalItem['kode_barang'] }}</td>
                                    <td>{{ $totalItem['nama_barang'] }}</td>
                                    <td>{{ $totalItem['total_in'] }} {{ $totalItem['code'] }}</td>
                                    <td>{{ $totalItem['total_out'] }} {{ $totalItem['code'] }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- Chart.js Bar Chart --}}
                <div class="chart-container">
                    @php
                        $chart = new QuickChart([
                            'width' => 500,
                            'height' => 300,
                        ]);

                        $chart->setConfig(
                            '
                    {
                        type: "bar",
                        data: {
                            labels: ' .
                                json_encode($chartData['labels']) .
                                ',
                            datasets: [
                                {
                                    label: "Barang Masuk",
                                    data: ' .
                                json_encode($chartData['dataIn']) .
                                ',
                                    backgroundColor: "rgba(0, 255, 0, 0.8)",
                                },
                                {
                                    label: "Barang Keluar",
                                    data: ' .
                                json_encode($chartData['dataOut']) .
                                ',
                                    backgroundColor: "rgba(255, 0, 0, 0.8)",
                                }
                            ]
                        },
                        options: {
                            plugins: {
                                datalabels: {
                                    display: function(context) {
                                        return context.dataset.data[context.dataIndex] !== 0;
                                    },
                                    backgroundColor: "#ccc",
                                    borderRadius: 3,
                                    font: {
                                    color: "red",
                                    weight: "bold",
                                },
                            },
                        },
                        },
                    }',
                        );

                        $chartFilePath = public_path('charts/chart' . $index . '.png');
                        $chart->toFile($chartFilePath);
                    @endphp

                    <img src="{{ public_path('charts/chart' . $index . '.png') }}" alt="chart" />
                </div>

                <hr class="section-divider">
            </div>
        @endforeach
    </div>
</body>

</html>
