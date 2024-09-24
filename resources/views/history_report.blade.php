<?php
header('Content-type: application/vnd-ms-excel');
header('Content-Disposition: attachment; filename=' . $filename . '');
?>

<div class="table-responsive">
    <table style="border-collapse: collapse; width: 100%;">
        <thead>
            <tr>
                <th style="border: 1px solid black; padding: 8px; background-color: #f2f2f2;">No</th>
                <th style="border: 1px solid black; padding: 8px;">Code</th>
                <th style="border: 1px solid black; padding: 8px;">Item Name</th>
                <th style="border: 1px solid black; padding: 8px;">Quantity</th>
                <th style="border: 1px solid black; padding: 8px;">Unit Price</th>
                <th style="border: 1px solid black; padding: 8px;">Total Price</th>
                <th style="border: 1px solid black; padding: 8px;">Category</th>
                <th style="border: 1px solid black; padding: 8px;">Input By</th>
                <th style="border: 1px solid black; padding: 8px;">Status</th>
                <th style="border: 1px solid black; padding: 8px;">Description</th>
                <th style="border: 1px solid black; padding: 8px;">Date</th>
            </tr>
        </thead>
        <tbody>
            @php
                $no = 1;
            @endphp
            @foreach ($items as $item)
                <tr>
                    <td style="border: 1px solid black; padding: 8px;">{{ $no++ }}</td>
                    <td style="border: 1px solid black; padding: 8px;">{{ $item->kode_barang }}</td>
                    <td style="border: 1px solid black; padding: 8px;">{{ $item->nama_barang }}</td>
                    <td style="border: 1px solid black; padding: 8px;">{{ $item->qty }} {{ $item->unit }}</td>
                    <td style="border: 1px solid black; padding: 8px;">{{ number_format($item->price, 0, ',', '.') }}
                    </td>
                    <td style="border: 1px solid black; padding: 8px;">
                        @if ($item->type == 'in')
                            {{ number_format($item->price * $item->qty, 0, ',', '.') }}
                        @else
                            0
                        @endif
                    </td>
                    <td style="border: 1px solid black; padding: 8px; text-transform: uppercase;">{{ $item->type }}
                    </td>
                    <td style="border: 1px solid black; padding: 8px;">{{ $item->user_name }}</td>
                    <td style="border: 1px solid black; padding: 8px;">
                        @if ($item->status == 0)
                            Pending
                        @elseif ($item->status == 1)
                            Approved
                        @else
                            Rejected
                        @endif
                    </td>
                    <td style="border: 1px solid black; padding: 8px;">{{ $item->description }}</td>
                    <td style="border: 1px solid black; padding: 8px;">{{ $item->tanggal }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
