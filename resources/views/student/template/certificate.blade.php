<html>

<head>
    <title>Certificate</title>

    <style>
        @font-face {
            font-family: 'Kokila';
            src: url('{{ asset('fonts/Kokila.ttf') }}') format('truetype');
            font-weight: normal;
            font-style: normal;
        }

        @font-face {
            font-family: 'OldEnglishTextMT';
            src: url('{{ asset('fonts/oldenglishtextmt.ttf') }}') format('truetype');
            font-weight: normal;
            font-style: normal;
        }

        @font-face {
            font-family: 'LohitDevanagari';
            src: url('{{ asset('fonts/Lohit-Devanagari.ttf') }}') format('truetype');
            font-weight: normal;
            font-style: normal;
        }

        @font-face {
            font-family: 'TiroDevanagariHindiRegular';
            src: url('{{ asset('fonts/TiroDevanagariHindi-Regular.ttf') }}') format('truetype');
            font-weight: normal;
            font-style: normal;
        }

        @font-face {
            font-family: 'TiroDevanagariHindiItalic';
            src: url('{{ asset('fonts/TiroDevanagariHindi-Italic.ttf') }}') format('truetype');
            font-weight: normal;
            font-style: normal;
        }

        @page {
            size: A4,
                margin: 0;
        }

        body {
            font-family: Arial, sans-serif, 'Times New Roman', Times, serif;
            color: #555557;
            background-color: #e0e0e0;
        }

        .container {
            /* width: 580px;
            margin: 0 auto;
            border: 1px solid #000;*/
            padding: 15px 92px;
            width: 210mm;
            height: 297mm;
            margin: 20px auto;
            /* padding: 20mm; */
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            box-sizing: border-box;
            background-color: #ffffff;
            border: 1px solid #ddd;
        }

        .text-center {
            text-align: center;
        }

        .text-justify {
            text-align: justify;
        }

        .text-right {
            text-align: right;
        }

        .footer {
            text-align: center;
            margin-top: 20px;
        }

        .strong {
            font-weight: bold;
        }

        .old-english-text-MT {
            font-family: 'OldEnglishTextMT', serif;
        }

        .hindi-heading {
            font-family: 'Kokila', 'LohitDevanagari', sans-serif;
            font-weight: bold;
            font-size: 1.8rem;
        }

        .hindi-text {
            font-family: 'Kokila', 'LohitDevanagari', 'TiroDevanagariHindiRegular', sans-serif;
            font-size: 1.52rem;
            line-height: 1.8;
        }

        .hindi-text strong {
            font-family: 'Kokila', 'LohitDevanagari', 'TiroDevanagariHindiItalic', sans-serif;
        }

        .english-heading {
            font-family: 'OldEnglishTextMT', serif;
        }

        .english-text {
            font-family: 'Times New Roman', Times, serif;
            font-size: 1.18rem;
        }

        .tiro-devanagari-hindi-italic {
            font-family: 'TiroDevanagariHindiItalic', serif;
        }

        .w-100 {
            width: 100%;
        }

        .d-flex {
            display: flex;
        }

        .d-flex:first-child {
            margin-right: 20px;
        }

        .d-flex div {
            flex: 1;
        }


        .justify-content-center {
            justify-content: center;
        }

        .justify-content-end {
            justify-content: flex-end;
        }

        .justify-content-between {
            justify-content: space-between;
        }

        .text-grey {
            color: #4c4c4d !important;
        }

        p {
            line-height: 1.8;
        }

        /* strong {
            text-decoration: underline;
            text-decoration-style: dotted;
        } */

        section {
            margin-top: 40px;
        }

        .qr-code {
            z-index: 9;
            margin-top: -70px;
            position: absolute;
        }

        .qr-code svg {
            z-index: 9;
        }

        @media print {
            .container {
                margin: auto !important;
                box-shadow: none;
                /* box-sizing: border-box;
                background-color: #ffffff; */
                border: none;
            }

            .letter-head {
                display: none;
            }

            .qr-code {
                margin-top: 30px;
                margin-bottom: 290px;
                position: relative;
            }


        }
    </style>
</head>

<body class="A4">
    <div class="container text-grey">
        @if ($status == 'success')
            <div class="d-flex justify-content-between">
                <div><span class="strong old-english-text-MT">Regd. No:</span> {{ $student->Registration_No }}</div>
                <div class="text-right">
                    <span class="strong old-english-text-MT" style="padding-right:90px;">Sl. No. </span>
                </div>
            </div>

            <div class="text-center letter-head" style="margin-top: 35px;z-index:-1">
                {{-- <h1 class="hindi-heading">राष्ट्रीय खेल विश्वविद्यालय मणिपुर, भारत</h1> --}}
                <img src="{{ asset('images/headingHindi.PNG') }}" alt="" height="80px" width="440px">
            </div>
            <div class="qr-code">
                {!! QrCode::size(65)->generate(route('showStudentDetails', $student->_id)) !!} {{-- Generate QR Code --}}
            </div>
            <div class="text-center letter-head" style="margin-top: -22px; z-index:10;">
                <img src="{{ asset('images/National_Sports_University_logo.png') }}" alt="" height="100"
                    width="100" />
                <h1 class="english-heading">
                    National Sports University, Manipur, <br />
                    India
                </h1>
            </div>

            <section class="hindi-section">
                <div class="text-center">
                    <h2 class="hindi-heading">
                        {{-- खेल कोचिंग में विज्ञान का स्नातक --}}
                        {{ $student->पाठ्यक्रम }}
                    </h2>
                </div>
                <p class="text-justify hindi-text">
                    प्रमाणित किया जाता है कि ......<strong
                        class="tiro-devanagari-hindi-italic">{{ $student->विभाग }}</strong>...... विभाग की
                    श्री/सुश्री ....<strong
                        class="tiro-devanagari-hindi-italic">{{ $student->छात्रों_का_नाम }}</strong>.....
                    ने इस
                    विश्वविद्यालय से
                    {{-- @if (!is_null($student->खेल) && trim($student->खेल) !== '') --}}
                    @if ($student->sport_required == true)
                        ...<strong class="tiro-devanagari-hindi-italic">{{ $student->खेल }}</strong>... में
                    @endif

                    <strong class="tiro-devanagari-hindi-italic">{{ $student->पाठ्यक्रम }}</strong>
                    डिग्री प्राप्त की है,
                    उन्होंने उक्त डिग्री के लिए ...
                    <strong class="tiro-devanagari-hindi-italic">{{ $student->महीना }}</strong> ..., ...
                    <strong>{{ $student->Year }}</strong>... में आयोजित परीक्षा ...
                    <strong>{{ $student->Grade }}</strong>... ग्रेड के साथ उत्तीर्ण की है।
                </p>
            </section>

            <section class="english-section">
                <div class="text-center">
                    <h2 class="old-english-text-MT">
                        {{-- Bachelor of Science in Sports Coaching --}}
                        {{ $student->Course }}
                    </h2>
                </div>

                <p class="text-justify english-text">

                    This is to certify that Mr/Ms …<strong>{{ $student->Name_of_Students }}</strong>… of the
                    Department of …<strong>{{ $student->Department }}</strong>..., has obtained the degree
                    in <strong>{{ $student->Course }}</strong>
                    {{-- @if (!is_null($student->Sports) && trim($student->Sports) !== '') --}}
                    @if ($student->sport_required == true)
                        in ... <strong>{{ $student->Sports }}</strong> ...
                    @endif
                    of this University, having passed the examination for
                    the
                    said degree held in ....<strong>{{ $student->Month }}</strong>...,
                    <strong>{{ $student->Year }}</strong>....
                    with ....<strong>{{ $student->Grade }}</strong>.... grade.

                </p>
            </section>
        @else
            <div class="text-center">
                <h4>Sorry, {{ $message }}</h4>
            </div>
        @endif

    </div>

</body>

</html>
