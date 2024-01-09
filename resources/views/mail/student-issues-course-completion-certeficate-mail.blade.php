<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="viewport" content="width=device-width">
    <title>index</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;700&amp;display=swap" rel="stylesheet">
    <style type="text/css">
        html {
            -webkit-text-size-adjust: none;
            -ms-text-size-adjust: none;
        }

        @media only screen and (max-device-width: 660px),
        only screen and (max-width: 660px) {
            .table800 {
                width: 100% !important;
            }

            .mob_100 {
                width: 100% !important;
                max-width: 100% !important;
            }
        }

        .table800 {
            width: 800px;
        }

        @media screen {
            .Inter400 {
                font-family: 'Inter', Arial, sans-serif !important;
                font-weight: 400 !important;
            }

            .Inter500 {
                font-family: 'Inter', Arial, sans-serif !important;
                font-weight: 500 !important;
            }

            .Inter700 {
                font-family: 'Inter', Arial, sans-serif !important;
                font-weight: 700 !important;
            }
        }
    </style>
</head>

<body style="margin: 0; padding: 0;">
    <span
        style="display: none !important; visibility: hidden; opacity: 0; color: #FFFFFF; height: 0; width: 0; font-size: 1px;">

    </span>
    <table cellpadding="0" cellspacing="0" border="0" width="100%"
        style="background: #FFFFFF; min-width: 360px; font-size: 1px; line-height: normal;">
        <tr em="group">
            <td align="center" valign="top">
                <!--[if (gte mso 9)|(IE)]>
    <table border="0" cellspacing="0" cellpadding="0">
    <tr><td align="center" valign="top" width="800"><![endif]-->
                <table cellpadding="0" cellspacing="0" border="0" width="800" class="table800"
                    style="width: 100%; max-width: 800px; min-width: 360px;">

                    <tr em="block">
                        <td align="center" valign="top" style="background: #FFFFFF;">
                            <table cellpadding="0" cellspacing="0" border="0" width="80%"
                                style="width: 80%; min-width: 330px;  ">
                                <tr>
                                    <td align="center">
                                        <div href="" target="_blank" style="display: block; max-width: 200px;">
                                            <img src="{{ asset(get_option('app_logo')) }}" width="640" border="0"
                                                alt="" style="display: block; width: 100%; max-width: 640px;">
                                        </div>

                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr em="block">
                        <td align="center" valign="top" style="background: #FFFFFF;">
                            <div style="height: 60px; line-height: 60px; font-size: 8px;">&nbsp;</div>
                            <table cellpadding="0" cellspacing="0" border="0" width="80%"
                                style="width: 80%; min-width: 330px; background: #449DCF;">
                                <tr>
                                    <td width="20" style="width: 20px;">&nbsp;</td>
                                    <td align="left">
                                        <div style="height: 10px; line-height: 10px; font-size: 8px;">&nbsp;</div>
                                        <div class="Inter700"
                                            style="font-family: Arial, sans-serif; color: #FFFFFF; font-size: 25px; line-height: 30px; font-weight: bold; text-align: center; ">
                                            {{ $data['email_title'] }}</div>
                                        <div style="height: 10px; line-height: 10px; font-size: 8px;">&nbsp;</div>
                                    </td>
                                    <td width="20" style="width: 20px;">&nbsp;</td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr em="block">
                        <td align="center" valign="top" style="background: #FFFFFF;">
                            <table cellpadding="0" cellspacing="0" border="0" width="75%"
                                style="width: 75%; min-width: 330px;">
                                <tr>
                                    <td align="left">
                                        <div style="height: 20px; line-height: 20px; font-size: 8px;">&nbsp;</div>
                                        <div class="Inter400"
                                            style="font-family: Arial, sans-serif; color: #171717; font-size: 15px; line-height: 21px;">
                                            Dear {{ $data['user_name'] }}
                                        </div>
                                        <div style="height: 20px; line-height: 20px; font-size: 8px;">&nbsp;</div>
                                        <div class="Inter400"
                                            style="font-family: Arial, sans-serif; color: #171717; font-size: 15px; line-height: 21px;">
                                            We hope this email finds you well. Congratulations on finishing the course
                                            {{ $data['course_name'] }} on {{ get_option('app_name') }}.
                                        </div>
                                        <div style="height: 20px; line-height: 20px; font-size: 8px;">&nbsp;</div>
                                        <div class="Inter400"
                                            style="font-family: Arial, sans-serif; color: #171717; font-size: 15px; line-height: 21px;">
                                            Log in to your account on {{ get_option('app_name') }} and navigate to your
                                            Consultations to download you certificate directly through the platform .
                                        </div>

                                        <div style="height: 20px; line-height: 20px; font-size: 8px;">&nbsp;</div>
                                        <div class="Inter400"
                                            style="font-family: Arial, sans-serif; color: #171717; font-size: 15px; line-height: 21px;">
                                            If you have any questions or concerns about the consultation or the services
                                            provided by us, please do not hesitate to reach out to us. Our customer
                                            support team is available to assist you with any issues or inquiries you may
                                            have.
                                        </div>

                                        <div style="height: 20px; line-height: 20px; font-size: 8px;">&nbsp;</div>
                                        <div class="Inter400"
                                            style="font-family: Arial, sans-serif; color: #171717; font-size: 15px; line-height: 21px;">
                                            Thank you for using {{ get_option('app_name') }} .
                                        </div>
                                        <div style="height: 20px; line-height: 20px; font-size: 8px;">&nbsp;</div>
                                        <div class="Inter400"
                                            style="font-family: Arial, sans-serif; color: #171717; font-size: 15px; line-height: 21px;">
                                            Best regards,
                                        </div>
                                        <div style="height: 20px; line-height: 20px; font-size: 8px;">&nbsp;</div>
                                        <div class="Inter400"
                                            style="font-family: Arial, sans-serif; color: #171717; font-size: 15px; line-height: 21px;">
                                            my-therapists.com Team.
                                        </div>


                                        <div style="height: 20px; line-height: 20px; font-size: 8px;">&nbsp;</div>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr em="block">
                        <td align="center" valign="top" style="background: #FFFFFF;">
                            <table cellpadding="0" cellspacing="0" border="0" width="255">
                                <tr>
                                    <td align="center" valign="middle" height="44"
                                        style="height: 44px; background: #449DCF;">
                                        <a href="{{ route('student.messages') }}" class="Inter700" target="_blank"
                                            style="display: table-cell; width: 255px; height: 44px; font-family: Arial, sans-serif; color: #ffffff; font-size: 15px; line-height: 15px; text-decoration: none; vertical-align: middle;">More
                                            info</a>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr em="block">
                        <td align="center" valign="top" style="background: #FFFFFF;">
                            <div style="height: 60px; line-height: 60px; font-size: 8px;">&nbsp;</div>
                            <table cellpadding="0" cellspacing="0" border="0" width="80%"
                                style="width: 80%; min-width: 330px; border-width: 2px; border-style: dashed; border-color: #449DCF;">
                                <tr>
                                    <td width="20" style="width: 20px;">&nbsp;</td>
                                    <td align="center">
                                        <div style="height: 40px; line-height: 40px; font-size: 8px;">&nbsp;</div>
                                        <div class="Inter700"
                                            style="font-family: Arial, sans-serif; color: #171717; font-size: 25px; line-height: 30px; font-weight: bold;">
                                            Book a&nbsp; session now</div>
                                        <div style="height: 20px; line-height: 20px; font-size: 8px;">&nbsp;</div>

                                        <div style="height: 20px; line-height: 20px; font-size: 8px;">&nbsp;</div>
                                        <table cellpadding="0" cellspacing="0" border="0" width="255">
                                            <tr>
                                                <td align="center" valign="middle" height="44"
                                                    style="height: 44px; background: #449DCF;">
                                                    <a href="{{ route('main.index') }}" class="Inter700"
                                                        target="_blank"
                                                        style="display: table-cell; width: 255px; height: 44px; font-family: Arial, sans-serif; color: #ffffff; font-size: 15px; line-height: 15px; text-decoration: none; vertical-align: middle;">Schedule
                                                        your session</a>
                                                </td>
                                            </tr>
                                        </table>
                                        <div style="height: 40px; line-height: 40px; font-size: 8px;">&nbsp;</div>
                                    </td>
                                    <td width="20" style="width: 20px;">&nbsp;</td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr em="block">
                        <td align="center" valign="top" style="background: #FFFFFF;">
                            <table cellpadding="0" cellspacing="0" border="0" width="80%"
                                style="width: 80%; min-width: 330px;">
                                <tr>
                                    <td align="center">
                                        <div style="height: 40px; line-height: 40px; font-size: 8px;">&nbsp;</div>
                                        <!--[if (gte mso 9)|(IE)]>
          <table border="0" cellspacing="0" cellpadding="0">
          <tr><td dir="ltr" align="center" valign="middle" width="310"><![endif]-->
                                        <div class="mob_100" dir="ltr"
                                            style="display: inline-block; vertical-align: middle; width: 310px;">
                                            <table cellpadding="0" cellspacing="0" border="0" width="100%">
                                                <tr>
                                                    <td align="center" valign="top">
                                                        <div style="height: 20px; line-height: 20px; font-size: 8px;">
                                                            &nbsp;</div>
                                                        <div href="" class="mob_100" target="_blank"
                                                            style="display: block; max-width: 290px;">
                                                            <img class="mob_100"
                                                                src="https://cdn.useblocks.io/1865/221025_18_91XUlw4.png"
                                                                width="290" border="0" alt=""
                                                                style="display: block; width: 100%; max-width: 290px;">
                                                        </div>
                                                    </td>
                                                </tr>
                                            </table>
                                        </div>
                                        <!--[if (gte mso 9)|(IE)]></td><td dir="ltr" align="center" valign="middle" width="310"><![endif]-->
                                        <div class="mob_100" dir="ltr"
                                            style="display: inline-block; vertical-align: middle; width: 310px;">
                                            <table class="mob_100" cellpadding="0" cellspacing="0" border="0"
                                                width="290">
                                                <tr>
                                                    <td align="left" valign="top">
                                                        <div style="height: 20px; line-height: 20px; font-size: 8px;">
                                                            &nbsp;</div>
                                                        <div class="Inter400"
                                                            style="font-family: Arial, sans-serif; color: #171717; font-size: 15px; line-height: 21px;">
                                                            A Better Solution for Mental health
                                                            Mental health is the key to Happiness ,Success and Harmony
                                                            Stability
                                                            Starts Here</div>
                                                        <div style="height: 20px; line-height: 20px; font-size: 8px;">
                                                            &nbsp;</div>
                                                        <table cellpadding="0" cellspacing="0" border="0"
                                                            width="255">
                                                            <tr>
                                                                <td align="center" valign="middle" height="44"
                                                                    style="height: 44px; background: #449DCF;">
                                                                    <a href="{{ route('main.index') }}"
                                                                        class="Inter700" target="_blank"
                                                                        style="display: table-cell; width: 255px; height: 44px; font-family: Arial, sans-serif; color: #ffffff; font-size: 15px; line-height: 15px; text-decoration: none; vertical-align: middle;">More
                                                                        info</a>
                                                                </td>
                                                            </tr>
                                                        </table>
                                                    </td>
                                                </tr>
                                            </table>
                                        </div>
                                        <!--[if (gte mso 9)|(IE)]>
          </td></tr>
          </table><![endif]-->
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr em="block">
                        <td align="center" valign="top" style="background: #FFFFFF;">
                            <table cellpadding="0" cellspacing="0" border="0" width="75%"
                                style="width: 75%; min-width: 330px;">
                                <tr>
                                    <td align="center">
                                        <div style="height: 60px; line-height: 60px; font-size: 8px;">&nbsp;</div>
                                        <div class="Inter700"
                                            style="font-family: Arial, sans-serif; color: #171717; font-size: 25px; line-height: 30px; font-weight: bold;">
                                            We hope this email was useful!</div>
                                        <div style="height: 35px; line-height: 35px; font-size: 8px;">&nbsp;</div>
                                        <table cellpadding="0" cellspacing="0" border="0" width="255">
                                            <tr>
                                                <td align="center" width="90">
                                                    <img src="https://cdn.useblocks.io/1865/221025_18_r0F2nxO.png"
                                                        width="90" border="0" alt=""
                                                        style="display: block; width: 100%; max-width: 90px;">
                                                    <div style="height: 14px; line-height: 14px; font-size: 8px;">
                                                        &nbsp;</div>
                                                </td>

                                            </tr>
                                        </table>
                                        <div style="height: 60px; line-height: 60px; font-size: 8px;">&nbsp;</div>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr em="block">
                        <td align="center" valign="top" style="background: #E5F4FD;">
                            <table cellpadding="0" cellspacing="0" border="0" width="75%"
                                style="width: 75%; min-width: 330px;">
                                <tr>
                                    <td align="center">
                                        <div style="height: 50px; line-height: 50px; font-size: 8px;">&nbsp;</div>
                                        <table cellpadding="0" cellspacing="0" border="0">
                                            <tr>
                                                <td align="center" valign="top" width="60"
                                                    style="width: 60px;">
                                                    <a href="{{ get_option('facebook_url') }}" target="_blank"
                                                        style="display: block; max-width: 40px;">
                                                        <img src="https://cdn.useblocks.io/1865/221025_18_Isx7bVH.png"
                                                            width="40" border="0" alt=""
                                                            style="display: block; width: 100%; max-width: 40px;">
                                                    </a>
                                                </td>
                                                <td align="center" valign="top" width="60"
                                                    style="width: 60px;">
                                                    <a href="{{ get_option('instagram_url') }}" target="_blank"
                                                        style="display: block; max-width: 40px;">
                                                        <img src="https://cdn.useblocks.io/1865/221025_18_QsbnXdT.png"
                                                            width="40" border="0" alt=""
                                                            style="display: block; width: 100%; max-width: 40px;">
                                                    </a>
                                                </td>
                                                <td align="center" valign="top" width="60"
                                                    style="width: 60px;">
                                                    <a href="{{ get_option('twitter_url') }}" target="_blank"
                                                        style="display: block; max-width: 40px;">
                                                        <img src="https://cdn.useblocks.io/1865/221025_18_A91nomb.png"
                                                            width="40" border="0" alt=""
                                                            style="display: block; width: 100%; max-width: 40px;">
                                                    </a>
                                                </td>
                                            </tr>
                                        </table>
                                        <div style="height: 40px; line-height: 40px; font-size: 8px;">&nbsp;</div>
                                        <div style="height: 15px; line-height: 15px; font-size: 8px;">&nbsp;</div>
                                        <div style="height: 40px; line-height: 40px; font-size: 8px;">&nbsp;</div>
                                        <a href="" target="_blank" style="display: block; max-width: 81px;">
                                            <img src="https://cdn.useblocks.io/1865/221025_18_ErHW8wV.png"
                                                width="81" border="0" alt=""
                                                style="display: block; width: 100%; max-width: 81px;">
                                        </a>
                                        <div style="height: 50px; line-height: 50px; font-size: 8px;">&nbsp;</div>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
                <!--[if (gte mso 9)|(IE)]>
    </td></tr>
    </table><![endif]-->
            </td>
        </tr>
    </table>

</body>

</html>
