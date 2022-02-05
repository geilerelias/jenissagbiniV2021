<?php

namespace App\Http\Controllers;

use Mail;
use Illuminate\Http\Request;

class ContactMessageController extends Controller
{


    /* public function store(Request $request)
    {
        if ($request->ajax()) {
            $this->validate($request, [
                'name' => 'required',
                'email' => 'required|email',
                'theme' => 'required',
                'message' => 'required',
            ]);

            $data = array('name' => $request->name, "subject" => $request->theme);

            Mail::send('emails.mail', $data, function ($message) {
                $message->from('contact@domainname.com', 'Company Name');
                $message->to('abc123@gmail.com ');
                $message->subject('Contact form submitted on domainname.com ');
            });

            Mail::send('emails.mail', $data, function ($message) {
                $message->from('john@johndoe.com', 'John Doe');
                $message->sender('john@johndoe.com', 'John Doe');
                $message->to('contacto@jenissagbini.com', 'Jenis Sagbini');
                $message->replyTo('john@johndoe.com', 'John Doe');
                $message->subject('Subject');
                $message->priority(3);
                $message->attach('pathToFile');
            });

            return json_encode(array('estate' => true, 'message' => 'mensaje enviado correctamente pronto estaremos en contacto con tigo ðŸ˜€'));
        } else {
            return view('inicio');
        }
    } */



    public function send(Request $request)
    {
        if ($request->ajax()) {
            $this->validate($request, [
                'name' => 'required',
                'email' => 'required|email',
                'theme' => 'required',
                'message' => 'required',
            ]);

            $name = $request->name;
            $email = $request->email;
            $message = $request->message;
            $emailTo = "contacto@jenissagbini.com";
            $theme = $request->theme;
            $cabeceras =  'MIME-Version: 1.0' . "\r\n";
            $cabeceras .= 'Content-type: text/html; charset=utf-8' . "\r\n";
            $cabeceras .= 'From:' . $email . "\r\n";


            $body = '<!DOCTYPE html>
            <html>
            <head><meta http-equiv="Content-Type" content="text/html; charset=utf-8">
            <meta name="viewport" content="width=device-width" />

            <title>Alerts e.g. approaching your limit</title>
            <style>
             *{margin:0;font-family:"Helvetica Neue",Helvetica,Arial,sans-serif;box-sizing:border-box;font-size:14px}img{max-width:100%}body{-webkit-font-smoothing:antialiased;-webkit-text-size-adjust:none;width:100%!important;height:100%;line-height:1.6em}table td{vertical-align:top}body{background-color:#f6f6f6}.body-wrap{background-color:#f6f6f6;width:100%}.container{display:block!important;max-width:600px!important;margin:0 auto!important;clear:both!important}.content{max-width:600px;margin:0 auto;display:block;padding:20px}.main{background-color:#fff;border:1px solid #e9e9e9;border-radius:3px}.content-wrap{padding:20px}.content-block{padding:0 0 20px}.header{width:100%;margin-bottom:20px}.footer{width:100%;clear:both;color:#999;padding:20px}.footer a,.footer p,.footer td{color:#999;font-size:12px}.last{margin-bottom:0}.first{margin-top:0}.aligncenter{text-align:center}.alignright{text-align:right}.alignleft{text-align:left}.clear{clear:both}.alert{font-size:16px;color:#fff;font-weight:500;padding:20px;text-align:center;border-radius:3px 3px 0 0}.alert a{color:#fff;font-weight:500;font-size:16px}.alert.alert-warning{background-color:#424242}.alert.alert-bad{background-color:#d0021b}.alert.alert-good{background-color:#68b90f}@media only screen and (max-width:640px){body{padding:0!important}h1,h2,h3,h4{font-weight:800!important;margin:20px 0 5px!important}h1{font-size:22px!important}h2{font-size:18px!important}h3{font-size:16px!important}.container{padding:0!important;width:100%!important}.content{padding:0!important}.content-wrap{padding:10px!important}}
              </style>
            </head>

            <body itemscope itemtype="http://schema.org/EmailMessage">

            <table class="body-wrap">
            	<tr>
            		<td></td>
            		<td class="container" width="600">
            			<div class="content">
            				<table class="main" width="100%" cellpadding="0" cellspacing="0">
            					<tr>
            						<td class="alert alert-warning">
            							Nuevo mensaje.
            						</td>
            					</tr>
            					<tr>
            						<td class="content-wrap">
            							<table width="100%" cellpadding="0" cellspacing="0">
            								<tr>
            									<td class="content-block">
            										 <strong>Asunto</strong> ' . $theme . '
            									</td>
            								</tr>
            								<tr>
            									<td class="content-block">
            										' . $message . '
            									</td>
            								</tr>

            								<tr>
            									<td class="content-block">
            										<b>Remitente:</b> ' . $name . '.
            									</td>
            								</tr>
                            	            <tr>
            									<td class="content-block">
            										<b>Email:</b> <a  href="' . $email . '">' . $email . '</a>.
            									</td>
            								</tr>
            							</table>
            						</td>
            					</tr>
            				</table>
            				<div class="footer">
            					<table width="100%">
            						<tr>
            							<td class="aligncenter content-block"> www.jenissagbini.com.</td>
            						</tr>
            					</table>
            				</div></div>
            		</td>
            		<td></td>
            	</tr>
            </table>

            </body>
            </html>';

            // send email
            $success = mail($emailTo, $theme, $body, $cabeceras);
            return $request->all();
        } else {
            return view('inicio');
        }
    }
}
