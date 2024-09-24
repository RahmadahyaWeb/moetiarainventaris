@extends('layouts.app')
@section('content')
    <div class="row">
        <div class="col-md-6 col-12">
            <div class="card">
                <div class="card-header fw-bold">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            Cashier Report
                        </div>
                    </div>
                    <hr>
                </div>
                <div class="card-body">
                    {{-- PDF --}}
                    <div class="tab-pane fade show active" id="pills-pdf" role="tabpanel" aria-labelledby="pills-pdf-tab"
                        tabindex="0">

                        <form id="formEdit" action="{{ route('reports.pdf', 'cashiers') }}" method="POST">
                            @csrf
                            <div class="row">
                                <div class="col-12 mb-3">
                                    <label for="fromDate" class="form-label">From Date</label>
                                    <input type="date" class="form-control @error('fromDate') is-invalid @enderror"
                                        id="fromDate" name="fromDate" value="{{ $today }}">
                                    @error('fromDate')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                                <div class="col-12 mb-3">
                                    <label for="toDate" class="form-label">To Date</label>
                                    <input type="date" class="form-control @error('toDate') is-invalid @enderror"
                                        id="toDate" name="toDate" value="{{ old('toDate') }}">
                                    @error('toDate')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                                <div class="col-12 mb-3 gap-2 d-flex justify-content-end">
                                    <button type="submit" class="btn btn-danger">
                                        <div id="submitText">
                                            PDF
                                        </div>
                                    </button>
                                    <a id="excelLink" href="{{ route('reports.excel') }}" class="btn btn-success">
                                        <div>
                                            Excel
                                        </div>
                                    </a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('fromDate').addEventListener('change', updateExcelLink);
        document.getElementById('toDate').addEventListener('change', updateExcelLink);
        document.getElementById('excelLink').addEventListener('click', validateAndExport);

        function updateExcelLink() {
            const fromDate = document.getElementById('fromDate').value;
            const toDate = document.getElementById('toDate').value;
            const excelLink = document.getElementById('excelLink');

            let url =
                `{{ route('reports.excel', ['fromDate' => ':fromDate', 'toDate' => ':toDate', 'division' => 'cashiers']) }}`;
            url = url.replace(':fromDate', fromDate).replace(':toDate', toDate).replace(':division', 'cashiers');
            console.log(url);
            excelLink.setAttribute('href', url);
        }

        function validateAndExport(event) {
            const fromDate = document.getElementById('fromDate').value;
            const toDate = document.getElementById('toDate').value;

            if (!fromDate || !toDate) {
                event.preventDefault();
                alert('Please fill in both From Date and End Date before exporting.');
            }
        }
    </script>
@endsection
