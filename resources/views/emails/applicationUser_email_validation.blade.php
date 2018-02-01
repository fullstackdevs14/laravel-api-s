@extends('emails.layout')
@section('title')
    <table cellspacing="0" cellpadding="0" class="force-full-width"
           style="background-color:#088A9B;">
        <tr>
            <td style="background-color:#088A9B;">

                <table cellspacing="0" cellpadding="0" class="force-full-width">
                    <tr>
                        <td style="font-size:40px; font-weight: 600; color: #ffffff; text-align:center;"
                            class="mobile-spacing">
                            <div class="mobile-br">&nbsp;</div>
                            Hello {{ $applicationUser_name }} !
                            Tu as presque terminé!
                            <br>
                        </td>
                    </tr>
                    <tr>
                        <td style="font-size:34px; text-align:center; padding: 0 75px; color:#ffffff;"
                            class="w320 mobile-spacing; ">
                            <a style="text-decoration: underline"
                               href="{{$base_url . $url_token}}">Clique ici pour finaliser
                                :)</a>
                        </td>
                    </tr>
                </table>

                <table cellspacing="0" cellpadding="0" width="600" class="force-full-width">
                    <tr>
                        <td>
                            <img src="{{$base_url}}img/emails/email_confirmation/ordinateur_mail_inscription.svg"
                                 style="max-width:100%; display:block;">
                        </td>
                    </tr>
                </table>

            </td>
        </tr>
    </table>
@endsection
@section('body')
    <table cellspacing="0" cellpadding="0" class="force-full-width" bgcolor="#ffffff">
        <tr>
            <td style="background-color:#ffffff;">
                <br>
                <table class="columns" cellspacing="0" cellpadding="0" width="49%"
                       align="left">
                    <tr>

                        <!-- ############# STEP ONE ############### -->
                        <!-- To change number images to step one:
                            - Replace image below with this url: https://www.filepicker.io/api/file/acgdn9j9T16oHaZ8znhv
                            - Then replace step two with this url: https://www.filepicker.io/api/file/iqmbVoMtT7ukbPUoo9zH
                            - Finally replace step three with this url: https://www.filepicker.io/api/file/ni2yEbRCRJKzRm3cYGnn

                            Finished!
                         -->
                        <td style="padding-left: 60px; padding-top: 10px;">
                            <img src="https://www.filepicker.io/api/file/zNDJy10QemuMhAcirOwQ"
                                 alt="step one" width="60" height="62">
                        </td>


                        <td style="color:#f3a389; text-align:left; padding-top: 10px;">
                            Active ton compte
                        </td>
                    </tr>
                    <tr>

                        <!-- ############# STEP TWO ############### -->
                        <!-- To change number images to step two:
                            - Replace image below with this url: https://www.filepicker.io/api/file/23h1I8Ts2PNLx755Dsfg
                            - Then replace step one with this url: https://www.filepicker.io/api/file/zNDJy10QemuMhAcirOwQ
                            - Finally replace step three with this url: https://www.filepicker.io/api/file/ni2yEbRCRJKzRm3cYGnn

                            Finished!
                         -->
                        <td style="padding-left: 60px; padding-top: 10px;">
                            <img src="https://www.filepicker.io/api/file/23h1I8Ts2PNLx755Dsfg"
                                 alt="step two" width="60" height="65">
                        </td>
                        <td style="color:#f5774e; text-align:left; padding-top: 10px;">
                            Choisi un bar
                        </td>
                    </tr>
                    <tr>

                        <!-- ############# STEP THREE ############### -->
                        <!-- To change number images to step three:
                            - Replace image below with this url: https://www.filepicker.io/api/file/OombIcyT92WWTaHB4vlE
                            - Then replace step one with this url: https://www.filepicker.io/api/file/zNDJy10QemuMhAcirOwQ
                            - Finally replace step three with this url: https://www.filepicker.io/api/file/iqmbVoMtT7ukbPUoo9zH

                            Finished!
                         -->
                        <td style="padding-left: 60px; padding-top: 10px;">
                            <img src="https://www.filepicker.io/api/file/ni2yEbRCRJKzRm3cYGnn"
                                 alt="step three" width="60" height="60">
                        </td>
                        <td style="color:#f3a389; text-align:left; padding-top: 10px;">
                            Passe commande sur <br>
                            la carte à partir de l'app
                        </td>
                    </tr>
                </table>
                <table class="columns" cellspacing="0" cellpadding="0" width="49%"
                       align="right">
                    <tr>
                        <td class="column-padding"
                            style="text-align:left; vertical-align:top; padding-left: 20px; padding-right:30px;">
                            <br>
                            <span style="color:#088A9B; font-size:20px; font-weight:bold;">Bienvenue dans l'équipe {{ \Illuminate\Support\Facades\Config::get('constants.company_name') }}
                                !</span><br>
                            Nous sommes actuellement entrain de travailler sur
                            l'application. Tu pourras bientôt faire bien plus qu'il n'est
                            déjà possible de faire aujourd'hui. En attendant nous te
                            souhaitons un bon moment entre amis chez nos bars partenaires !
                            <br/>
                            <br/>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
@endsection