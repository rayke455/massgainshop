@extends(template().'layouts.error')
@section('title', '419')

@section('content')
    <style>
        .error-section {
            min-height: 100vh;
            background: linear-gradient(135deg, #f8fafc, #e2e8f0);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 60px 20px;
            text-align: center;
        }

        .error-wrapper {
            max-width: 900px;
            margin: auto;
            background: #ffffff;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.05);
            padding: 40px 30px;
            position: relative;
            overflow: hidden;
        }

        .error-wrapper::before {
            content: '';
            position: absolute;
            width: 300px;
            height: 300px;
            background: radial-gradient(#cbd5e1, transparent 70%);
            border-radius: 50%;
            top: -100px;
            right: -100px;
            z-index: 0;
        }

        .error-info {
            font-size: 20px;
            color: #4b5563;
            margin-bottom: 30px;
            position: relative;
            z-index: 1;
        }

        .error-thumb img {
            max-width: 300px;
            animation: float 3s ease-in-out infinite;
            margin-bottom: 20px;
            position: relative;
            z-index: 1;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
        }

        @media (min-width: 768px) {
            .error-wrapper {
                display: flex;
                align-items: center;
                text-align: left;
            }

            .error-thumb {
                flex: 1;
                text-align: center;
            }

            .error-content {
                flex: 1;
                padding-left: 40px;
            }
        }

        .error-section .error-content .error-info {
            font-size: 40px;
            line-height: 1.3;
        }

        .btn {
            background: linear-gradient(109deg, #c64fff 0%, #7b3fff 100%);
            color: #fff;
            border: none;
        }

        .btn:hover {
            color: #fff;
        }
    </style>

    <section class="error-section">
        <div class="error-wrapper">
            <div class="error-thumb">
                <img src="{{ asset(template(true).'img/error/419.png') }}" alt="419 Image">
            </div>
            <div class="error-content">
                <div class="error-info">@lang("Sorry, your session has expired. Please log in again.")</div>
                <a href="{{ url('/') }}" class="btn btn-xl rounded-2">@lang("Back to Home")</a>
            </div>
        </div>
    </section>
@endsection

