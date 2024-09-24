<?php
header('Content-type: application/vnd-ms-excel');
header('Content-Disposition: attachment; filename=' . $filename . '');
?>

<table style="border-collapse: collapse; width: 100%;">
    <thead>
        <tr>
            <th rowspan="2" style="border: 1px solid black;">No</th>
            <th rowspan="2" style="border: 1px solid black;">Jenis Barang</th>
            <th rowspan="2" style="border: 1px solid black;">Stock Minimal</th>
            <th rowspan="2" style="border: 1px solid black;">Satuan</th>
            <th rowspan="2" style="border: 1px solid black;">Harga Satuan</th>
            @foreach ($tanggalArray as $date)
                <th colspan="3" style="border: 1px solid black;">{{ \Carbon\Carbon::parse($date)->format('d/m/Y') }}
                </th>
            @endforeach
        </tr>
        <tr>
            @foreach ($tanggalArray as $date)
                <th style="border: 1px solid black;">In</th>
                <th style="border: 1px solid black;">Out</th>
                <th style="border: 1px solid black;">Stock</th>
            @endforeach
        </tr>
    </thead>
    <tbody>
        @php
            $displayedCodes = []; // Array to keep track of displayed kode_barang
            $no = 1;
        @endphp

        @foreach ($items as $key => $item)
            @if (!in_array($item->kode_barang, $displayedCodes))
                <tr>
                    <td style="border: 1px solid black;">{{ $no++ }}</td>
                    <td style="border: 1px solid black;">{{ $item->nama_barang }}</td>
                    <td style="border: 1px solid black;">{{ $item->minimum }}</td>
                    <td style="border: 1px solid black;">{{ $item->code }}</td>
                    <td style="border: 1px solid black;">{{ $item->price }}</td>

                    @php
                        $sisaStok = 0;
                    @endphp

                    @foreach ($tanggalArray as $date)
                        @php
                            $totalIn = 0; // Initialize totalIn
                            $totalOut = 0; // Initialize totalOut

                            // Assuming you have grouped data
                            if ($groupedData->has($date)) {
                                foreach ($groupedData[$date] as $entry) {
                                    if ($entry->kode_barang == $item->kode_barang) {
                                        $totalIn = $entry->total_in;
                                        $totalOut = $entry->total_out;
                                        break;
                                    }
                                }
                            }

                            $sisaStok = $sisaStok + $totalIn - $totalOut; // Update remaining stock
                        @endphp
                        <td style="border: 1px solid black;">{{ $totalIn }}</td>
                        <td style="border: 1px solid black;">{{ $totalOut }}</td>
                        <td style="border: 1px solid black;">{{ $sisaStok }}</td>
                    @endforeach
                </tr>
                @php
                    $displayedCodes[] = $item->kode_barang; // Add kode_barang to the displayed list
                @endphp
            @endif
        @endforeach
    </tbody>
</table>
