@extends('layouts.app')

@section('title', 'DKS Scan')

@section('content')
    <div class="card">
        <div class="card-header">
            <div class="row align-items-center">
                <div class="col">
                    <b>DKS Monitoring</b>
                </div>
                <div class="col d-flex justify-content-end">
                    <a href="" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalScan">
                        <i class="bx bx-qr-scan me-2"></i>
                        Scan
                    </a>
                </div>
            </div>
            <hr>
        </div>
        <div class="card-body">
            <div class="mb-3">
                <b> {{ $today = \Carbon\Carbon::now()->format('d-m-Y') }}</b> | Jangan lupa scan katalog.
            </div>
            @livewire('dks-table')
        </div>
        <div class="card-footer">
            <small>
                NB: sebelum ingin istirahat, harus menuliskan keterangan <b>IST/ist</b> saat checkout di toko sebelum
                istirahat.
            </small>
        </div>
    </div>

    <!-- Modal SCAN-->
    <div class="modal fade" id="modalScan" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">DKS Scan</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="placeholder" class="placeholder text-center">
                        <p>Click "Start Scanning" to begin.</p>
                    </div>

                    <div id="loading" class="text-center d-none">
                        <div class="spinner-border" role="status"></div>
                        <div>Loading...</div>
                    </div>

                    <div id="reader" class="img-fluid mb-3"></div>

                    <div id="result" class="mb-3"></div>

                    <div class="d-grid">
                        <button id="start-button" class="btn btn-success">Start Scanning</button>
                        <button id="stop-button" class="btn btn-danger d-none" style="display: none;">Stop Scanning</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="tqModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="tqModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="tqModalLabel">Pilih Tempat</h1>
                </div>
                <div class="modal-body">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="Tq" id="TQ">
                        <label class="form-check-label" for="TQ">
                            Sinar Taqwa Motor 1
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="Tq" id="TQ2" checked>
                        <label class="form-check-label" for="TQ2">
                            Sinar Taqwa Motor 2
                        </label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" id="confirmSelection">Konfirmasi</button>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            const html5QrCode = new Html5Qrcode("reader");
            let scanning = false;

            function getRandomString(length) {
                const characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
                let result = '';
                for (let i = 0; i < length; i++) {
                    result += characters.charAt(Math.floor(Math.random() * characters.length));
                }
                return result;
            }

            document.getElementById("start-button").addEventListener("click", () => {
                document.getElementById("start-button").setAttribute('disabled', 'true');

                function getQrBoxSize() {
                    const width = window.innerWidth;
                    const height = window.innerHeight;
                    const qrBoxSize = Math.min(width, height) * 0.25;
                    return {
                        width: Math.max(qrBoxSize, 200),
                        height: Math.max(qrBoxSize, 200)
                    };
                }

                Html5Qrcode.getCameras().then(devices => {
                    if (devices && devices.length) {
                        var cameraId = devices[0].id;
                        const config = {
                            aspectRatio: 1,
                            qrbox: getQrBoxSize(),
                        };

                        const qrCodeSuccessCallback = (decodedText, decodedResult) => {
                            const url = new URL(decodedText);
                            const kd_toko = url.searchParams.get('kd_toko');
                            const encrypted = btoa(kd_toko);
                            const katalog = url.searchParams.get('Katalog');

                            // let katalogEncrypted = '';

                            function generateRandomString(length) {
                                const characters =
                                    'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
                                let result = '';
                                for (let i = 0; i < length; i++) {
                                    const randomIndex = Math.floor(Math.random() * characters.length);
                                    result += characters[randomIndex];
                                }
                                return result;
                            }

                            let randomString = generateRandomString(20);

                            const katalogEncrypted = randomString.slice(0, 6) + katalog + randomString.slice(6);  

                            const redirectUrl = `/dks-scan/${encrypted}?katalog=${katalogEncrypted}`;
                            document.getElementById("loading").classList.remove('d-none');
                            document.getElementById("stop-button").classList.add('d-none');

                            html5QrCode.stop().then(() => {
                                if (kd_toko == 'TQ') {
                                    $('#modalScan').modal('hide');
                                    $('#tqModal').modal('show');

                                    document.getElementById('confirmSelection').onclick = () => {
                                        const selectedOption = document.querySelector(
                                            'input[name="Tq"]:checked').id;

                                        $('#tqModal').modal('hide');

                                        window.location.href =
                                            `/dks-scan/${btoa(selectedOption)}?katalog=${katalogEncrypted}`;
                                    };
                                } else {
                                    window.location.href = redirectUrl;
                                }
                            });
                        };

                        html5QrCode.start({
                            facingMode: {
                                exact: "environment"
                                // exact: "user"
                            }
                        }, config, qrCodeSuccessCallback).then(() => {
                            scanning = true;
                            document.getElementById("start-button").removeAttribute('disabled');
                            document.getElementById("start-button").classList.add('d-none');
                            document.getElementById("stop-button").classList.remove('d-none');
                            document.getElementById("placeholder").classList.add('d-none');
                        }).catch(err => {
                            document.getElementById("result").innerText =
                                `Error starting scanner: ${err}`;
                        });
                    } else {
                        document.getElementById("result").innerText = "No camera found.";
                    }
                }).catch(err => {
                    document.getElementById("result").innerText = "Camera access denied or not available.";
                });
            });

            document.getElementById("stop-button").addEventListener("click", () => {
                if (scanning) {
                    html5QrCode.stop().then(() => {
                        scanning = false;
                        document.getElementById("start-button").classList.remove('d-none');
                        document.getElementById("stop-button").classList.add('d-none');
                        document.getElementById("placeholder").classList.remove('d-none');
                    }).catch(err => {
                        document.getElementById("result").innerText = `Error stopping scanner: ${err}`;
                    });
                }
            });
        </script>
    @endpush

@endsection
