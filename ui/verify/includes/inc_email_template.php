<?php 
$email_template = '<!doctype html>
<head>
    <!-- NAME: FOLLOW UP -->
    <!--[if gte mso 15]> <xml> <o:OfficeDocumentSettings> <o:AllowPNG/> <o:PixelsPerInch>96</o:PixelsPerInch> </o:OfficeDocumentSettings> </xml> <![endif]-->
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Automation 3 Pre Op</title>
    <style type="text/css">
    p {
        margin: 10px 0;
        padding: 0;
    }

    table {
        border-collapse: collapse;
    }

    h1,
    h2,
    h3,
    h4,
    h5,
    h6 {
        display: block;
        margin: 0;
        padding: 0;
    }

    img,
    a img {
        border: 0;
        height: auto;
        outline: none;
        text-decoration: none;
    }

    body,
    #bodyTable,
    #bodyCell {
        height: 100%;
        margin: 0;
        padding: 0;
        width: 100%;
    }

    .mcnPreviewText {
        display: none !important;
    }

    #outlook a {
        padding: 0;
    }

    img {
        -ms-interpolation-mode: bicubic;
    }

    table {
        mso-table-lspace: 0pt;
        mso-table-rspace: 0pt;
    }

    .ReadMsgBody {
        width: 100%;
    }

    .ExternalClass {
        width: 100%;
    }

    p,
    a,
    li,
    td,
    blockquote {
        mso-line-height-rule: exactly;
    }

    a[href^=tel],
    a[href^=sms] {
        color: inherit;
        cursor: default;
        text-decoration: none;
    }

    p,
    a,
    li,
    td,
    body,
    table,
    blockquote {
        -ms-text-size-adjust: 100%;
        -webkit-text-size-adjust: 100%;
    }

    .ExternalClass,
    .ExternalClass p,
    .ExternalClass td,
    .ExternalClass div,
    .ExternalClass span,
    .ExternalClass font {
        line-height: 100%;
    }

    a[x-apple-data-detectors] {
        color: inherit !important;
        text-decoration: none !important;
        font-size: inherit !important;
        font-family: inherit !important;
        font-weight: inherit !important;
        line-height: inherit !important;
    }

    .templateContainer {
        max-width: 600px !important;
    }

    a.mcnButton {
        display: block;
    }

    .mcnImage {
        vertical-align: bottom;
    }

    .mcnTextContent {
        word-break: break-word;
    }

    .mcnTextContent img {
        height: auto !important;
    }

    .mcnDividerBlock {
        table-layout: fixed !important;
    }
    /* @tab Page @section Heading 1 @style heading 1 */

    h1 {
        /*@editable*/
        color: #222222;
        /*@editable*/
        font-family: Helvetica;
        /*@editable*/
        font-size: 36px;
        /*@editable*/
        font-style: normal;
        /*@editable*/
        font-weight: bold;
        /*@editable*/
        line-height: 150%;
        /*@editable*/
        letter-spacing: normal;
        /*@editable*/
        text-align: center;
    }
    /* @tab Page @section Heading 2 @style heading 2 */

    h2 {
        /*@editable*/
        color: #222222;
        /*@editable*/
        font-family: Helvetica;
        /*@editable*/
        font-size: 34px;
        /*@editable*/
        font-style: normal;
        /*@editable*/
        font-weight: bold;
        /*@editable*/
        line-height: 150%;
        /*@editable*/
        letter-spacing: normal;
        /*@editable*/
        text-align: left;
    }
    /* @tab Page @section Heading 3 @style heading 3 */

    h3 {
        /*@editable*/
        color: #444444;
        /*@editable*/
        font-family: Helvetica;
        /*@editable*/
        font-size: 22px;
        /*@editable*/
        font-style: normal;
        /*@editable*/
        font-weight: bold;
        /*@editable*/
        line-height: 150%;
        /*@editable*/
        letter-spacing: normal;
        /*@editable*/
        text-align: center;
    }
    /* @tab Page @section Heading 4 @style heading 4 */

    h4 {
        /*@editable*/
        color: #999999;
        /*@editable*/
        font-family: Georgia;
        /*@editable*/
        font-size: 20px;
        /*@editable*/
        font-style: italic;
        /*@editable*/
        font-weight: normal;
        /*@editable*/
        line-height: 125%;
        /*@editable*/
        letter-spacing: normal;
        /*@editable*/
        text-align: left;
    }
    /* @tab Header @section Header Container Style */

    #templateHeader {
        /*@editable*/
        background-color: #F7F7F7;
        /*@editable*/
        background-image: none;
        /*@editable*/
        background-repeat: no-repeat;
        /*@editable*/
        background-position: 50% 50%;
        /*@editable*/
        background-size: cover;
        /*@editable*/
        border-top: 0;
        /*@editable*/
        border-bottom: 0;
        /*@editable*/
        padding-top: 0px;
        /*@editable*/
        padding-bottom: 0px;
    }
    /* @tab Header @section Header Interior Style */

    .headerContainer {
        /*@editable*/
        background-color: transparent;
        /*@editable*/
        background-image: none;
        /*@editable*/
        background-repeat: no-repeat;
        /*@editable*/
        background-position: center;
        /*@editable*/
        background-size: cover;
        /*@editable*/
        border-top: 0;
        /*@editable*/
        border-bottom: 0;
        /*@editable*/
        padding-top: 0;
        /*@editable*/
        padding-bottom: 0;
    }
    /* @tab Header @section Header Text */

    .headerContainer .mcnTextContent,
    .headerContainer .mcnTextContent p {
        /*@editable*/
        color: #808080;
        /*@editable*/
        font-family: Helvetica;
        /*@editable*/
        font-size: 16px;
        /*@editable*/
        line-height: 150%;
        /*@editable*/
        text-align: left;
    }
    /* @tab Header @section Header Link */

    .headerContainer .mcnTextContent a,
    .headerContainer .mcnTextContent p a {
        /*@editable*/
        color: #00ADD8;
        /*@editable*/
        font-weight: normal;
        /*@editable*/
        text-decoration: underline;
    }
    /* @tab Body @section Body Container Style */

    #templateBody {
        /*@editable*/
        background-color: #f5f5f5;
        /*@editable*/
        background-image: none;
        /*@editable*/
        background-repeat: no-repeat;
        /*@editable*/
        background-position: center;
        /*@editable*/
        background-size: cover;
        /*@editable*/
        border-top: 0;
        /*@editable*/
        border-bottom: 0;
        /*@editable*/
        padding-top: 27px;
        /*@editable*/
        padding-bottom: 0px;
    }
    /* @tab Body @section Body Interior Style */

    .bodyContainer {
        /*@editable*/
        background-color: transparent;
        /*@editable*/
        background-image: none;
        /*@editable*/
        background-repeat: no-repeat;
        /*@editable*/
        background-position: center;
        /*@editable*/
        background-size: cover;
        /*@editable*/
        border-top: 0;
        /*@editable*/
        border-bottom: 0;
        /*@editable*/
        padding-top: 0;
        /*@editable*/
        padding-bottom: 0;
    }
    /* @tab Body @section Body Text */

    .bodyContainer .mcnTextContent,
    .bodyContainer .mcnTextContent p {
        /*@editable*/
        color: #808080;
        /*@editable*/
        font-family: Helvetica;
        /*@editable*/
        font-size: 16px;
        /*@editable*/
        line-height: 150%;
        /*@editable*/
        text-align: left;
    }
    /* @tab Body @section Body Link */

    .bodyContainer .mcnTextContent a,
    .bodyContainer .mcnTextContent p a {
        /*@editable*/
        color: #00ADD8;
        /*@editable*/
        font-weight: normal;
        /*@editable*/
        text-decoration: underline;
    }
    /* @tab Footer @section Footer Style */

    #templateFooter {
        /*@editable*/
        background-color: #f5f5f5;
        /*@editable*/
        background-image: none;
        /*@editable*/
        background-repeat: no-repeat;
        /*@editable*/
        background-position: center;
        /*@editable*/
        background-size: cover;
        /*@editable*/
        border-top: 0;
        /*@editable*/
        border-bottom: 0;
        /*@editable*/
        padding-top: 45px;
        /*@editable*/
        padding-bottom: 63px;
    }
    /* @tab Footer @section Footer Interior Style */

    .footerContainer {
        /*@editable*/
        background-color: transparent;
        /*@editable*/
        background-image: none;
        /*@editable*/
        background-repeat: no-repeat;
        /*@editable*/
        background-position: center;
        /*@editable*/
        background-size: cover;
        /*@editable*/
        border-top: 0;
        /*@editable*/
        border-bottom: 0;
        /*@editable*/
        padding-top: 0;
        /*@editable*/
        padding-bottom: 0;
    }
    /* @tab Footer @section Footer Text */

    .footerContainer .mcnTextContent,
    .footerContainer .mcnTextContent p {
        /*@editable*/
        color: #FFFFFF;
        /*@editable*/
        font-family: Helvetica;
        /*@editable*/
        font-size: 12px;
        /*@editable*/
        line-height: 150%;
        /*@editable*/
        text-align: center;
    }
    /* @tab Footer @section Footer Link */

    .footerContainer .mcnTextContent a,
    .footerContainer .mcnTextContent p a {
        /*@editable*/
        color: #FFFFFF;
        /*@editable*/
        font-weight: normal;
        /*@editable*/
        text-decoration: underline;
    }

    @media only screen and (min-width: 620px) {
        .templateContainer {
            width: 600px !important;
        }
    }

    @media only screen and (max-width: 480px) {
        body,
        table,
        td,
        p,
        a,
        li,
        blockquote {
            -webkit-text-size-adjust: none !important;
        }
    }

    @media only screen and (max-width: 480px) {
        body {
            width: 100% !important;
            min-width: 100% !important;
        }
    }

    @media only screen and (max-width: 480px) {
        .mcnImage {
            width: 100% !important;
        }
    }

    @media only screen and (max-width: 480px) {
        .mcnCartContainer,
        .mcnCaptionTopContent,
        .mcnRecContentContainer,
        .mcnCaptionBottomContent,
        .mcnTextContentContainer,
        .mcnBoxedTextContentContainer,
        .mcnImageGroupContentContainer,
        .mcnCaptionLeftTextContentContainer,
        .mcnCaptionRightTextContentContainer,
        .mcnCaptionLeftImageContentContainer,
        .mcnCaptionRightImageContentContainer,
        .mcnImageCardLeftTextContentContainer,
        .mcnImageCardRightTextContentContainer {
            max-width: 100% !important;
            width: 100% !important;
        }
    }

    @media only screen and (max-width: 480px) {
        .mcnBoxedTextContentContainer {
            min-width: 100% !important;
        }
    }

    @media only screen and (max-width: 480px) {
        .mcnImageGroupContent {
            padding: 9px !important;
        }
    }

    @media only screen and (max-width: 480px) {
        .mcnCaptionLeftContentOuter .mcnTextContent,
        .mcnCaptionRightContentOuter .mcnTextContent {
            padding-top: 9px !important;
        }
    }

    @media only screen and (max-width: 480px) {
        .mcnImageCardTopImageContent,
        .mcnCaptionBlockInner .mcnCaptionTopContent:last-child .mcnTextContent {
            padding-top: 18px !important;
        }
    }

    @media only screen and (max-width: 480px) {
        .mcnImageCardBottomImageContent {
            padding-bottom: 9px !important;
        }
    }

    @media only screen and (max-width: 480px) {
        .mcnImageGroupBlockInner {
            padding-top: 0 !important;
            padding-bottom: 0 !important;
        }
    }

    @media only screen and (max-width: 480px) {
        .mcnImageGroupBlockOuter {
            padding-top: 9px !important;
            padding-bottom: 9px !important;
        }
    }

    @media only screen and (max-width: 480px) {
        .mcnTextContent,
        .mcnBoxedTextContentColumn {
            padding-right: 18px !important;
            padding-left: 18px !important;
        }
    }

    @media only screen and (max-width: 480px) {
        .mcnImageCardLeftImageContent,
        .mcnImageCardRightImageContent {
            padding-right: 18px !important;
            padding-bottom: 0 !important;
            padding-left: 18px !important;
        }
    }

    @media only screen and (max-width: 480px) {
        .mcpreview-image-uploader {
            display: none !important;
            width: 100% !important;
        }
    }

    @media only screen and (max-width: 480px) {
        /* @tab Mobile Styles @section Heading 1 @tip Make the first-level headings larger in size for better readability on small screens. */
        h1 {
            /*@editable*/
            font-size: 30px !important;
            /*@editable*/
            line-height: 125% !important;
        }
    }

    @media only screen and (max-width: 480px) {
        /* @tab Mobile Styles @section Heading 2 @tip Make the second-level headings larger in size for better readability on small screens. */
        h2 {
            /*@editable*/
            font-size: 26px !important;
            /*@editable*/
            line-height: 125% !important;
        }
    }

    @media only screen and (max-width: 480px) {
        /* @tab Mobile Styles @section Heading 3 @tip Make the third-level headings larger in size for better readability on small screens. */
        h3 {
            /*@editable*/
            font-size: 20px !important;
            /*@editable*/
            line-height: 150% !important;
        }
    }

    @media only screen and (max-width: 480px) {
        /* @tab Mobile Styles @section Heading 4 @tip Make the fourth-level headings larger in size for better readability on small screens. */
        h4 {
            /*@editable*/
            font-size: 18px !important;
            /*@editable*/
            line-height: 150% !important;
        }
    }

    @media only screen and (max-width: 480px) {
        /* @tab Mobile Styles @section Boxed Text @tip Make the boxed text larger in size for better readability on small screens. We recommend a font size of at least 16px. */
        .mcnBoxedTextContentContainer .mcnTextContent,
        .mcnBoxedTextContentContainer .mcnTextContent p {
            /*@editable*/
            font-size: 14px !important;
            /*@editable*/
            line-height: 150% !important;
        }
    }

    @media only screen and (max-width: 480px) {
        /* @tab Mobile Styles @section Header Text @tip Make the header text larger in size for better readability on small screens. */
        .headerContainer .mcnTextContent,
        .headerContainer .mcnTextContent p {
            /*@editable*/
            font-size: 16px !important;
            /*@editable*/
            line-height: 150% !important;
        }
    }

    @media only screen and (max-width: 480px) {
        /* @tab Mobile Styles @section Body Text @tip Make the body text larger in size for better readability on small screens. We recommend a font size of at least 16px. */
        .bodyContainer .mcnTextContent,
        .bodyContainer .mcnTextContent p {
            /*@editable*/
            font-size: 16px !important;
            /*@editable*/
            line-height: 150% !important;
        }
    }

    @media only screen and (max-width: 480px) {
        /* @tab Mobile Styles @section Footer Text @tip Make the footer content text larger in size for better readability on small screens. */
        .footerContainer .mcnTextContent,
        .footerContainer .mcnTextContent p {
            /*@editable*/
            font-size: 14px !important;
            /*@editable*/
            line-height: 150% !important;
        }
    }
    </style>
</head>

<body>
    <!--*|IF:MC_PREVIEW_TEXT|*-->
    <!--[if !gte mso 9]><!----><span class="mcnPreviewText" style="display:none; font-size:0px; line-height:0px; max-height:0px; max-width:0px; opacity:0; overflow:hidden; visibility:hidden; mso-hide:all;">Hello **FIRSTNAME**, **CONTENT1**</span>
    <!--<![endif]-->
    <!--*|END:IF|*-->
    <center>
        <table align="center" border="0" cellpadding="0" cellspacing="0" height="100%" width="100%" id="bodyTable">
            <tr>
                <td align="center" valign="top" id="bodyCell">
                    <!-- BEGIN TEMPLATE // -->
                    <table border="0" cellpadding="0" cellspacing="0" width="100%">
                        <tr>
                            <td align="center" valign="top" style="background-color:#f5f5f5" id="templateHeader" data-template-container>
                                <!--[if gte mso 9]> <table align="center" border="0" cellspacing="0" cellpadding="0" width="600" style="width:600px;"> <tr> <td align="center" valign="top" width="600" style="width:600px;"> <![endif]-->
                                <table align="center" border="0" cellpadding="0" cellspacing="0" width="100%" class="templateContainer">
                                    <tr>
                                        <td valign="top" class="headerContainer">
                                            <table border="0" cellpadding="0" cellspacing="0" width="100%" class="mcnImageBlock" style="min-width:100%;">
                                                <tbody class="mcnImageBlockOuter">
                                                    <tr>
                                                        <td valign="top" style="padding:9px" class="mcnImageBlockInner">
                                                            <table align="left" width="100%" border="0" cellpadding="0" cellspacing="0" class="mcnImageContentContainer" style="min-width:100%;">
                                                                <tbody>
                                                                    <tr>
                                                                        <td class="mcnImageContent" valign="top" style="padding-right: 9px; padding-left: 9px; padding-top: 0; padding-bottom: 0; text-align:center;"> <img src="https://widgixeu-library.s3.amazonaws.com/library/90006956/EIDO_Verify_Logo_568px.png" align="right" alt="" width="183" style="line-height:inherit;max-width:183px;border-width:0;display:block;height:auto;width:100%;"> </td>
                                                                    </tr>
                                                                </tbody>
                                                            </table>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </td>
                                    </tr>
                                </table>
                                <!--[if gte mso 9]> </td> </tr> </table> <![endif]-->
                            </td>
                        </tr>
                        <tr>
                            <td align="center" valign="top" id="templateBody" data-template-container>
                                <!--[if gte mso 9]> <table align="center" border="0" cellspacing="0" cellpadding="0" width="600" style="width:600px;"> <tr> <td align="center" valign="top" width="600" style="width:600px;"> <![endif]-->
                                <table align="center" border="0" cellpadding="0" cellspacing="0" width="100%" class="templateContainer">
                                    <tr>
                                        <td valign="top" class="bodyContainer" style="background-color:#ffffff;">
                                            <table border="0" cellpadding="0" cellspacing="0" width="100%" class="mcnTextBlock" style="min-width:100%;">
                                                <tbody class="mcnTextBlockOuter">
                                                    <tr>
                                                        <td valign="top" class="mcnTextBlockInner" style="padding-top:9px;">
                                                            <!--[if mso]> <table align="left" border="0" cellspacing="0" cellpadding="0" width="100%" style="width:100%;"> <tr> <![endif]-->
                                                            <!--[if mso]> <td valign="top" width="600" style="width:600px;"> <![endif]-->
                                                            <table align="left" border="0" cellpadding="0" cellspacing="0" style="max-width:100%; min-width:100%;" width="100%" class="mcnTextContentContainer">
                                                                <tbody>
                                                                    <tr>
                                                                        <td valign="top" class="mcnTextContent" style="padding-top:15px; padding-right:18px; padding-bottom:27px; padding-left:18px; text-align:center">
                                                                            <h1>**HEADER**</h1> </td>
                                                                    </tr>
                                                                </tbody>
                                                            </table>
                                                            <!--[if mso]> </td> <![endif]-->
                                                            <!--[if mso]> </tr> </table> <![endif]-->
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                            <table border="0" cellpadding="0" cellspacing="0" width="100%" class="mcnTextBlock" style="min-width:100%; color:#808080">
                                                <tbody class="mcnTextBlockOuter">
                                                    <tr>
                                                        <td valign="top" class="mcnTextBlockInner" style="padding-top:9px;">
                                                            <!--[if mso]> <table align="left" border="0" cellspacing="0" cellpadding="0" width="100%" style="width:100%;"> <tr> <![endif]-->
                                                            <!--[if mso]> <td valign="top" width="600" style="width:600px;"> <![endif]-->
                                                            <table align="left" border="0" cellpadding="0" cellspacing="0" style="max-width:100%; min-width:100%;" width="100%" class="mcnTextContentContainer">
                                                                <tbody>
                                                                    <tr>
                                                                        <td valign="top" class="mcnTextContent" style="padding-top:0; padding-right:18px; padding-bottom:9px; padding-left:18px;">
                                                                            <p><span style="font-family:lato,helvetica neue,helvetica,arial,sans-serif"><span style="font-size:16px">Hello **FIRSTNAME**,</span></span>
                                                                            </p>
                                                                            <p><span style="font-family:lato,helvetica neue,helvetica,arial,sans-serif"><span style="font-size:16px">**CONTENT2**</span></span>
                                                                            </p>
                                                                        </td>
                                                                    </tr>
                                                                </tbody>
                                                            </table>
                                                            <!--[if mso]> </td> <![endif]-->
                                                            <!--[if mso]> </tr> </table> <![endif]-->
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                            <table border="0" cellpadding="0" cellspacing="0" width="100%" class="mcnDividerBlock" style="min-width:100%;">
                                                <tbody class="mcnDividerBlockOuter">
                                                    <tr>
                                                        <td class="mcnDividerBlockInner" style="min-width: 100%; padding: 9px 18px 0px;">
                                                            <table class="mcnDividerContent" border="0" cellpadding="0" cellspacing="0" width="100%" style="min-width:100%;">
                                                                <tbody>
                                                                    <tr>
                                                                        <td> <span></span> </td>
                                                                    </tr>
                                                                </tbody>
                                                            </table>
                                                            <!-- <td class="mcnDividerBlockInner" style="padding: 18px;"> <hr class="mcnDividerContent" style="border-bottom-color:none; border-left-color:none; border-right-color:none; border-bottom-width:0; border-left-width:0; border-right-width:0; margin-top:0; margin-right:0; margin-bottom:0; margin-left:0;" /> --></td>
                                                    </tr>
                                                </tbody>
                                            </table>
                     **EMAILBUTTON**
                                            <table border="0" cellpadding="0" cellspacing="0" width="100%" class="mcnTextBlock" style="min-width:100%; color:#808080">
                                                <tbody class="mcnTextBlockOuter">
                                                    <tr>
                                                        <td valign="top" class="mcnTextBlockInner" style="padding-top:9px;">
                                                            <!--[if mso]> <table align="left" border="0" cellspacing="0" cellpadding="0" width="100%" style="width:100%;"> <tr> <![endif]-->
                                                            <!--[if mso]> <td valign="top" width="600" style="width:600px;"> <![endif]-->
                                                            <table align="left" border="0" cellpadding="0" cellspacing="0" style="max-width:100%; min-width:100%;" width="100%" class="mcnTextContentContainer">
                                                                <tbody>
                                                                    <tr>
                                                                        <td valign="top" class="mcnTextContent" style="padding-top:0; padding-right:18px; padding-bottom:9px; padding-left:18px;">
                                                                            <p><span style="font-family:lato,helvetica neue,helvetica,arial,sans-serif"><span style="font-size:16px">**CONTENT3**</span></span>
                                                                            </p>
                                                                        </td>
                                                                    </tr>
                                                                </tbody>
                                                            </table>
                                                            <!--[if mso]> </td> <![endif]-->
                                                            <!--[if mso]> </tr> </table> <![endif]-->
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                            <table border="0" cellpadding="0" cellspacing="0" width="100%" class="mcnDividerBlock" style="min-width:100%;">
                                                <tbody class="mcnDividerBlockOuter">
                                                    <tr>
                                                        <td class="mcnDividerBlockInner" style="min-width: 100%; padding: 18px 18px 0px;">
                                                            <table class="mcnDividerContent" border="0" cellpadding="0" cellspacing="0" width="100%" style="min-width:100%;">
                                                                <tbody>
                                                                    <tr>
                                                                        <td> <span></span> </td>
                                                                    </tr>
                                                                </tbody>
                                                            </table>
                                                            <!-- <td class="mcnDividerBlockInner" style="padding: 18px;"> <hr class="mcnDividerContent" style="border-bottom-color:none; border-left-color:none; border-right-color:none; border-bottom-width:0; border-left-width:0; border-right-width:0; margin-top:0; margin-right:0; margin-bottom:0; margin-left:0;" /> --></td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                            <table border="0" cellpadding="0" cellspacing="0" width="100%" class="mcnBoxedTextBlock" style="min-width:100%;">
                                                <!--[if gte mso 9]> <table align="center" border="0" cellspacing="0" cellpadding="0" width="100%"> <![endif]-->
                                                <tbody class="mcnBoxedTextBlockOuter">
                                                    <tr>
                                                        <td valign="top" class="mcnBoxedTextBlockInner">
                                                            <!--[if gte mso 9]> <td align="center" valign="top" "> <![endif]-->
                                                            <table align="left" border="0" cellpadding="0" cellspacing="0" width="100%" style="min-width:100%;" class="mcnBoxedTextContentContainer">
                                                                <tbody>
                                                                    <tr>
                                                                        <td style="padding-top:9px; padding-left:18px; padding-bottom:9px; padding-right:18px;">
                                                                            <table border="0" cellpadding="18" cellspacing="0" class="mcnTextContentContainer" width="100%" style="min-width: 100% !important;background-color: #FFFFFF;">
                                                                                <tbody>
                                                                                    <tr>
                                                                                        <td valign="top" class="mcnTextContent" style="color: #808080;font-family: Helvetica;font-size: 16px;line-height: 200%;text-align: center;">
                                                                                            <p lang="x-size-12" style="text-align: center;"><span style="font-size:12px"><span style="font-family:lato,helvetica neue,helvetica,arial,sans-serif"><strong>What is EIDO Verify?</strong></span></span>
                                                                                            </p>
                                                                                            <p lang="x-size-12" style="text-align: center;"><span style="font-size:12px"><span style="font-family:lato,helvetica neue,helvetica,arial,sans-serif">EIDO Verify is a website that provides information about hospital procedures and asks questions to check your current state of health.</span></span>
                                                                                            </p>
                                                                                            <p lang="x-size-12" style="text-align: center;"><span style="font-size:12px"><span style="font-family:lato,helvetica neue,helvetica,arial,sans-serif">The information you provide is sent back to the hospital, to help with diagnosis and treatment. For more information on how it works,&nbsp;<a href="https://www.eidoverify.com/about/" target="_blank">click here</a></span></span>
                                                                                            </p>
                                                                                        </td>
                                                                                    </tr>
                                                                                </tbody>
                                                                            </table>
                                                                        </td>
                                                                    </tr>
                                                                </tbody>
                                                            </table>
                                                            <!--[if gte mso 9]> </td> <![endif]-->
                                                            <!--[if gte mso 9]> </tr> </table> <![endif]-->
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </td>
                                    </tr>
                                </table>
                                <!--[if gte mso 9]> </td> </tr> </table> <![endif]-->
                            </td>
                        </tr>
                        <tr>
                            <td align="center" valign="top" id="templateFooter" data-template-container>
                                <!--[if gte mso 9]> <table align="center" border="0" cellspacing="0" cellpadding="0" width="600" style="width:600px;"> <tr> <td align="center" valign="top" width="600" style="width:600px;"> <![endif]-->
                                <table align="center" border="0" cellpadding="0" cellspacing="0" width="100%" class="templateContainer">
                                    <tr>
                                        <td valign="top" class="footerContainer">
                                            <table border="0" cellpadding="0" cellspacing="0" width="100%" class="mcnTextBlock" style="min-width:100%;">
                                                <tbody class="mcnTextBlockOuter">
                                                    <tr>
                                                        <td valign="top" class="mcnTextBlockInner" style="padding-top:9px;">
                                                            <!--[if mso]> <table align="left" border="0" cellspacing="0" cellpadding="0" width="100%" style="width:100%;"> <tr> <![endif]-->
                                                            <!--[if mso]> <td valign="top" width="600" style="width:600px;"> <![endif]-->
                                                            <table align="left" border="0" cellpadding="0" cellspacing="0" style="max-width:100%; min-width:100%;" width="100%" class="mcnTextContentContainer">
                                                                <tbody>
                                                                    <tr>
                                                                        <td valign="top" class="mcnTextContent" style="padding: 0px 18px 9px;color: #93A6AD;">
                                                                            <div style="text-align: left;"><span style="font-size:12px"><span style="font-family:lato,helvetica neue,helvetica,arial,sans-serif">EIDO Healthcare Ltd, 19-21 Main Street, Keyworth, Nottinghamshire, NG12 5AA<br> You are receiving this email, as your hospital has subscribed you.</span></span>
                                                                            </div>
                                                                            <div style="text-align: left;"><span style="font-size:12px"><span style="font-family:lato,helvetica neue,helvetica,arial,sans-serif">Unsubscribe</span></span>
                                                                            </div>
                                                                        </td>
                                                                    </tr>
                                                                </tbody>
                                                            </table>
                                                            <!--[if mso]> </td> <![endif]-->
                                                            <!--[if mso]> </tr> </table> <![endif]-->
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </td>
                                    </tr>
                                </table>
                                <!--[if gte mso 9]> </td> </tr> </table> <![endif]-->
                            </td>
                        </tr>
                    </table>
                    <!-- // END TEMPLATE -->
                </td>
            </tr>
        </table>
    </center>
</body>';
?>

