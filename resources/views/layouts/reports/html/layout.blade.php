<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="author" content="{{ $author or config('app.name') }}">
    <meta name="description" content="{{ $description or config('app.name') }}">
    <title>{{ $title or config('app.name') }}</title>
</head>
<body>
<style>
    /* Base */

    body, body *:not(html):not(style):not(br):not(tr):not(code) {
        font-family: Avenir, Helvetica, sans-serif;
        box-sizing: border-box;
    }

    body {
        background-color: #f5f8fa;
        color: #74787E;
        height: 100%;
        hyphens: auto;
        line-height: 1.4;
        margin: 0;
        -moz-hyphens: auto;
        -ms-word-break: break-all;
        width: 100% !important;
        -webkit-hyphens: auto;
        -webkit-text-size-adjust: none;
        word-break: break-all;
        word-break: break-word;
    }

    p,
    ul,
    ol,
    blockquote {
        line-height: 1.4;
        text-align: left;
    }

    a {
        color: #3869D4;
    }

    a img {
        border: none;
    }

    /* Typography */

    h1 {
        color: #2F3133;
        font-size: 19px;
        font-weight: bold;
        margin-top: 0;
        text-align: left;
    }

    h2 {
        color: #2F3133;
        font-size: 16px;
        font-weight: bold;
        margin-top: 0;
        text-align: left;
    }

    h3 {
        color: #2F3133;
        font-size: 14px;
        font-weight: bold;
        margin-top: 0;
        text-align: left;
    }

    p {
        color: #74787E;
        font-size: 16px;
        line-height: 1.5em;
        margin-top: 0;
        text-align: left;
    }

    p.sub {
        font-size: 12px;
    }

    img {
        max-width: 100%;
    }

    /* Layout */

    .wrapper {
        background-color: #f5f8fa;
        margin: 0;
        padding: 0;
        width: 100%;
        -premailer-cellpadding: 0;
        -premailer-cellspacing: 0;
        -premailer-width: 100%;
    }

    .content {
        margin: 0;
        padding: 0;
        width: 100%;
        -premailer-cellpadding: 0;
        -premailer-cellspacing: 0;
        -premailer-width: 100%;
    }

    /* Header */

    .header {
        padding: 25px 0;
        text-align: center;
    }

    .header a {
        color: #bbbfc3;
        font-size: 19px;
        font-weight: bold;
        text-decoration: none;
        text-shadow: 0 1px 0 white;
    }

    /* Body */

    .body {
        background-color: #FFFFFF;
        border-bottom: 1px solid #EDEFF2;
        border-top: 1px solid #EDEFF2;
        margin: 0;
        padding: 0;
        width: 100%;
        -premailer-cellpadding: 0;
        -premailer-cellspacing: 0;
        -premailer-width: 100%;
    }

    .inner-body {
        background-color: #FFFFFF;
        margin: 0 auto;
        padding: 0;
        width: 570px;
        -premailer-cellpadding: 0;
        -premailer-cellspacing: 0;
        -premailer-width: 570px;
    }

    /* Subcopy */

    .subcopy {
        border-top: 1px solid #EDEFF2;
        margin-top: 25px;
        padding-top: 25px;
    }

    .subcopy p {
        font-size: 12px;
    }

    /* Footer */

    .footer {
        margin: 0 auto;
        padding: 0;
        text-align: center;
        width: 570px;
        -premailer-cellpadding: 0;
        -premailer-cellspacing: 0;
        -premailer-width: 570px;
    }

    .footer p {
        color: #AEAEAE;
        font-size: 12px;
        text-align: center;
    }

    /* Tables */

    .table table {
        margin: 30px auto;
        width: 100%;
        -premailer-cellpadding: 0;
        -premailer-cellspacing: 0;
        -premailer-width: 100%;
    }

    .table th {
        border-bottom: 1px solid #EDEFF2;
        padding-bottom: 8px;
        margin: 0;
    }

    .table th[scope="row"] {
        border-bottom: none;
        font-size: 15px;
        line-height: 18px;
        padding: 10px 0;
    }

    .table td {
        color: #74787E;
        font-size: 15px;
        line-height: 18px;
        padding: 10px 0;
        margin: 0;
    }

    .table-bordered,.table-bordered td,.table-bordered th {
        border: 1px solid #a4b7c1
    }

    .table-bordered thead td,.table-bordered thead th {
        border-bottom-width: 2px
    }

    .table-borderless tbody+tbody,.table-borderless td,.table-borderless th,.table-borderless thead th {
        border: 0
    }

    .table-striped tbody tr:nth-of-type(odd) {
        background-color: rgba(0,0,0,.05)
    }

    .table-primary,.table-primary>td,.table-primary>th {
        background-color: #c1e7f4
    }

    .table-secondary,.table-secondary>td,.table-secondary>th {
        background-color: #e6ebee
    }

    .table-success,.table-success>td,.table-success>th {
        background-color: #cdedd8
    }

    .table-info,.table-info>td,.table-info>th {
        background-color: #d3eef6
    }

    .table-warning,.table-warning>td,.table-warning>th {
        background-color: #ffeeba
    }

    .table-danger,.table-danger>td,.table-danger>th {
        background-color: #fdd6d6
    }

    .table-light,.table-light>td,.table-light>th {
        background-color: #fbfcfc
    }

    .table-dark,.table-dark>td,.table-dark>th {
        background-color: #c3c7c9
    }

    .table .thead-dark th {
        color: #e4e5e6;
        background-color: #151b1e;
        border-color: #252f35
    }

    .table .thead-light th {
        color: #3e515b;
        background-color: #c2cfd6;
        border-color: #a4b7c1
    }

    .table-dark {
        color: #e4e5e6;
        background-color: #151b1e
    }

    .table-dark td,.table-dark th,.table-dark thead th {
        border-color: #252f35
    }

    .table-dark.table-bordered {
        border: 0
    }

    .table-dark.table-striped tbody tr:nth-of-type(odd) {
        background-color: hsla(0,0%,100%,.05)
    }

    .content-cell {
        padding: 35px;
    }

    /* Legend */
    ul.legend {
        list-style: none;
        clear: both;
        padding: 0;
    }

    ul.legend > li {
        display: inline-block;
        padding: 10px 15px;
    }

    /* Badge */
    .badge {
        display: inline-block;
        padding: .25em .4em;
        font-size: 75%;
        font-weight: 700;
        line-height: 1;
        text-align: center;
        white-space: nowrap;
        vertical-align: baseline
    }

    .badge.legend {
        width: 8px;
        height: 8px;
        padding: 0
    }

    .badge:empty {
        display: none
    }

    .btn .badge {
        position: relative;
        top: -1px
    }

    .badge-pill {
        padding-right: .6em;
        padding-left: .6em
    }

    .badge-primary {
        color: #fff;
        background-color: #20a8d8
    }

    .badge-primary[href]:focus,.badge-primary[href]:hover {
        color: #fff;
        text-decoration: none;
        background-color: #1985ac
    }

    .badge-secondary {
        color: #151b1e;
        background-color: #a4b7c1
    }

    .badge-secondary[href]:focus,.badge-secondary[href]:hover {
        color: #151b1e;
        text-decoration: none;
        background-color: #869fac
    }

    .badge-success {
        color: #fff;
        background-color: #4dbd74
    }

    .badge-success[href]:focus,.badge-success[href]:hover {
        color: #fff;
        text-decoration: none;
        background-color: #3a9d5d
    }

    .badge-info {
        color: #151b1e;
        background-color: #63c2de
    }

    .badge-info[href]:focus,.badge-info[href]:hover {
        color: #151b1e;
        text-decoration: none;
        background-color: #39b2d5
    }

    .badge-warning {
        color: #151b1e;
        background-color: #ffc107
    }

    .badge-warning[href]:focus,.badge-warning[href]:hover {
        color: #151b1e;
        text-decoration: none;
        background-color: #d39e00
    }

    .badge-danger {
        color: #fff;
        background-color: #f86c6b
    }

    .badge-danger[href]:focus,.badge-danger[href]:hover {
        color: #fff;
        text-decoration: none;
        background-color: #f63c3a
    }

    .badge-light {
        color: #151b1e;
        background-color: #f0f3f5
    }

    .badge-light[href]:focus,.badge-light[href]:hover {
        color: #151b1e;
        text-decoration: none;
        background-color: #d1dbe1
    }

    .badge-dark {
        color: #fff;
        background-color: #29363d
    }

    .badge-dark[href]:focus,.badge-dark[href]:hover {
        color: #fff;
        text-decoration: none;
        background-color: #151b1f
    }

    /* Buttons */

    .action {
        margin: 30px auto;
        padding: 0;
        text-align: center;
        width: 100%;
        -premailer-cellpadding: 0;
        -premailer-cellspacing: 0;
        -premailer-width: 100%;
    }

    .button {
        border-radius: 3px;
        box-shadow: 0 2px 3px rgba(0, 0, 0, 0.16);
        color: #FFF;
        display: inline-block;
        text-decoration: none;
        -webkit-text-size-adjust: none;
    }

    .button-blue {
        background-color: #3097D1;
        border-top: 10px solid #3097D1;
        border-right: 18px solid #3097D1;
        border-bottom: 10px solid #3097D1;
        border-left: 18px solid #3097D1;
    }

    .button-green {
        background-color: #2ab27b;
        border-top: 10px solid #2ab27b;
        border-right: 18px solid #2ab27b;
        border-bottom: 10px solid #2ab27b;
        border-left: 18px solid #2ab27b;
    }

    .button-red {
        background-color: #bf5329;
        border-top: 10px solid #bf5329;
        border-right: 18px solid #bf5329;
        border-bottom: 10px solid #bf5329;
        border-left: 18px solid #bf5329;
    }

    /* Panels */

    .panel {
        margin: 0 0 21px;
    }

    .panel-content {
        background-color: #EDEFF2;
        padding: 16px;
    }

    .panel-item {
        padding: 0;
    }

    .panel-item p:last-of-type {
        margin-bottom: 0;
        padding-bottom: 0;
    }

    /* Promotions */

    .promotion {
        background-color: #FFFFFF;
        border: 2px dashed #9BA2AB;
        margin: 0;
        margin-bottom: 25px;
        margin-top: 25px;
        padding: 24px;
        width: 100%;
        -premailer-cellpadding: 0;
        -premailer-cellspacing: 0;
        -premailer-width: 100%;
    }

    .promotion h1 {
        text-align: center;
    }

    .promotion p {
        font-size: 15px;
        text-align: center;
    }

    /* Content */

    hr {
        border: 0.3px solid;
        margin: 1.4rem 0;
        color: #ddd;
    }

    .text-left {
        text-align: left;
    }

    .text-center {
        text-align: center;
    }

    .text-right {
        text-align: right;
    }

    .page-break-before {
        page-break-before: always;
    }

    .page-break-after {
        page-break-after: always;
    }

    @media only screen and (max-width: 600px) {
        .inner-body {
            width: 100% !important;
        }

        .footer {
            width: 100% !important;
        }
    }

    @media only screen and (max-width: 500px) {
        .button {
            width: 100% !important;
        }
    }
</style>

<table class="wrapper" width="100%" cellpadding="0" cellspacing="0">
    <tr>
        <td align="center">
            <table class="content" width="100%" cellpadding="0" cellspacing="0">
            {{ $header or '' }}

            <!-- Email Body -->
                <tr>
                    <td class="body" width="100%" cellpadding="0" cellspacing="0">
                        <table class="content" align="center" width="100%" cellpadding="0" cellspacing="0">
                            <!-- Body content -->
                            <tr>
                                <td class="content-cell">
                                    {{ $slot }}

                                    {{ $subcopy or '' }}
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>

                {{ $footer or '' }}
            </table>
        </td>
    </tr>
</table>
</body>
</html>
