<?php

class PigArray implements ArrayAccess {
    private $array;
    private $default;

    public function __construct($array, $default = NULL) {
        $this->array   = $array;
        $this->default = $default;
    }

    public function offsetExists($offset) {
        return isset($this->array[$offset]);
    }

    public function offsetGet($offset) {
        return isset($this->array[$offset]) 
            ? $this->array[$offset] 
            : $this->default
            ;
    }

    public function offsetSet($offset, $value) {
        $this->array[$offset] = $value;
    }

    public function offsetUnset($offset) {
        unset($this->array[$offset]);
    }
}

require_once('../../../../wp-load.php');

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    
    $results = $wpdb->get_results( 'SELECT * FROM wp_microfilm', OBJECT );
    echo json_encode($results);
}
elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // 'pasword' is a fake field to prevent bot activity. It should always be empty
    if ( !empty($_POST['password']) ) {
        die();
    }

    require_once('../functions/forms/payment-gateway.php');

    $post = new PigArray($_POST, '');

    $microfilms = $post['microfilms'];

    // set form price in NIS
    $prices = get_field('acf-options_services_costs', 'option');
    foreach ($prices as $entry) {
        if ( strtolower($entry['service_id']) == 'lds' ) {
            $paymentData['total'] = $entry['cost'];
        }
    }

    try {
        
        $pelecardRequest = $pelecardApi->makePayment($paymentData, 'lds');
        $pelecardResponse = $pelecardRequest->getResponse();
        $transactionID = $pelecardResponse->getID();

        if ( $pelecardResponse->isSuccessful() ) {

            // generate microfilm html table
            $table =
                '<table>'.
                    '<thead>'.
                        '<tr>'.
                            '<th>Film</th>'.
                            '<th>Type</th>'.
                            '<th>Year</th>'.
                            '<th>Act</th>'.
                            '<th>Town</th>'.
                            '<th>Family Name</th>'.
                            '<th>Given Name</th>'.
                        '</tr>'.
                    '</thead>'.
                    '<tbody>';
 
            foreach($microfilms as $microfilm) {
                $tr =
                    '<tr>'.
                        '<td>' . $microfilm['Microfilm']    . '</td>'.
                        '<td>' . $microfilm['chosenType']   . '</td>'.
                        '<td>' . $microfilm['Year']         . '</td>'.
                        '<td>' . $microfilm['Act']          . '</td>'.
                        '<td>' . $microfilm['Town']         . '</td>'.
                        '<td>' . $microfilm['FamilyName']   . '</td>'.
                        '<td>' . $microfilm['GivenName']    . '</td>'.
                    '</tr>';

                $table .= $tr;
            }
            $table .= '</tbody></table>';

            $message_head = 
                '<head>'.
                    '<style>'.
                        'table, th, td {'.
                            'border: 1px solid #666666;'.
                        '}'.
                        'table {'.
                            'border-collapse: collapse;'.
                        '}'.
                        'th, td {'.
                            'padding: 3px;'.
                        '}'.
                    '</style>'.
                '</head>';


            $message_table =
                '<table>'.
                    '<tbody>'.
                        '<tr>'.
                            '<td>Microfilm request</td>'.
                            '<td>'. $table . '</td>'.
                        '</tr>'.
                        '<tr>'.
                            '<td>Full Name</td>'.
                            '<td>' . $post['fullname'] . '</td>'.
                        '</tr>'.
                        '<tr>'.
                            '<td>Institution</td>'.
                            '<td>' . $post['institution'] . '</td>'.
                        '</tr>'.
                        '<tr>'.
                            '<td>Address</td>'.
                            '<td>' . $post['address'] . '</td>'.
                        '</tr>'.
                        '<tr>'.
                            '<td>City</td>'.
                            '<td>' . $post['city'] . '</td>'.
                        '</tr>'.
                        '<tr>'.
                            '<td>Country</td>'.
                            '<td>' . $post['country'] . '</td>'.
                        '</tr>'.
                        '<tr>'.
                            '<td>State</td>'.
                            '<td>' . $post['state'] . '</td>'.
                        '</tr>'.
                        '<tr>'.
                            '<td>Province</td>'.
                            '<td>' . $post['province'] . '</td>'.
                        '</tr>'.
                        '<tr>'.
                            '<td>Zip / Postal code</td>'.
                            '<td>' . $post['zipcode'] . '</td>'.
                        '</tr>'.
                        '<tr>'.
                            '<td>Phone</td>'.
                            '<td>' . $post['phone'] . '</td>'.
                        '</tr>'.
                        '<tr>'.
                            '<td>Additional Phone</td>'.
                            '<td>' . $post['addphone'] . '</td>'.
                        '</tr>'.
                        '<tr>'.
                            '<td>Email</td>'.
                            '<td>' . $post['email'] . '</td>'.
                        '</tr>'.
                        '<tr>'.
                            '<td>Transaction ID</td>'.
                            '<td>' . $transactionID . '</td>'.
                        '</tr>'.
                        '<tr>'.
                            '<td>Credit Company</td>'.
                            '<td>' . $pelecardResponse->getCreditCompany() . '</td>'.
                        '</tr>'.
                        '<tr>'.
                            '<td>Total</td>'.
                            '<td>' . strval( $pelecardResponse->getTotal() ) . ' ' . strtoupper( $pelecardApi->getCurrencyName( $pelecardResponse->getCurrencyCode() ) ) . '</td>'.
                        '</tr>'.
                    '</tbody>'.
                '</table>';

            $message = '<html>' . $message_head . '<body>' . $message_table . '</body>' . '</html>';

            // send email
            $to = 'danna@bh.org.il,genealogy@bh.org.il,bhformsbackup@gmail.com,dannyb@bh.org.il';
            $sentmail = send_mail($to, 'Microfilm Request', $post['email'], $post['fullname'], $message);

            if ($sentmail) {
                
                // send confirmation email
                $confirmation_message = 
                    '<html>'.
                        $message_head.
                        '<body>'.
                            '<div style="background-color:#eeeeee; color: #666666; padding: 5px; text-align:center; font-size:14px;">'.
                                '<div style="padding: 0 0 5px; display:inline-block; text-align:left;">'.
                                    '<div style="background-color:#ffffff; margin: 10px auto 15px auto; padding: 5px;">'.
                                        '<img src="http://www.bh.org.il/wp-content/themes/bh/images/general/logo-en-big.png" alt="logo"><br />'.
                                        'Dear Visitor,<br />'.
                                        '<br />'.
                                        'Thank you for your order no. ' . $transactionID . ' of records from the LDS microfilms collection at Beit Hatfutsot.<br />'.
                                        'We will process your request and send you scans of the requested records within 14 business days, to the e-mail address mentioned in your order.<br />'.
                                        'Please add <a href="http://www.bh.org.il">our website</a> to your favorites and keep visiting us regularly.<br /><br />'.
                                        'Please see below the list of the requested records.<br />'.
                                        '<br />'.
                                        'The Douglas E. Goldman Jewish Genealogy Center<br />'.
                                        'Beit Hatfusot - The Museum of the Jewish People<br />'.
                                        '<br />'.
                                        '<div style="direction: rtl; text-align: right;">'.
                                        'שלום רב,<br />'.
                                        '<br />'.
                                        'תודה ששלחתם הזמנה מס\' ' . $transactionID . ' של צילומי תעודות מאוסף המיקרופילמים  LDS.<br />'.
                                        'אנו נטפל בבקשה בהקדם האפשרי ונשלח אליכם את הסריקות שנמצאו, בתוך 14 ימי עבודה, אל כתובת הדוא"ל שציינתם בהזמנה.<br />'.
                                        'נשמח אם תצרפו את <a href="http://www.bh.org.il">האתר שלנו</a> לרשימת המועדפים שלכם, ותבקרו בו גם בעתיד.<br /><br />'.
                                        'להלן פרטי ההזמנה כפי שהתקבלו ע"י הצוות שלנו.<br />'.
                                        '<br />'.
                                        'בברכה,<br />'.
                                        'המרכז לגניאלוגיה יהודית ע"ש דגלס א. גולדמן<br />'.
                                        'בית התפוצות - מוזיאון העם היהודי<br />'.
                                        '</div>'.
                                        '<br />'.
                                        '<br />'.
                                        $message_table.
                                        '<br />'.
                                    '</div>'.
                                    'You received this message, because a form had been submitted on Beit Hatfutsot website (www.bh.org.il), using this email address.<br />'.
                                    'If you did not submit a form on the above site, please disregard this message.<br />'.
                                    '<br />'.
                                    'This email was automatically generated and sent from a notification-only address. Please do not reply to this message.'.
                                '</div>'.
                            '</div>'.
                        '</body>'.
                    '</html>';

                send_mail($post['email'], 'LDS Order Confirmation', 'no-reply@bh.org.il', 'Beit Hatfutsot', $confirmation_message);

                $response = array(
                    'success' => true
                );
            }
            else {

                $response = array(
                    'error' => array(
                        'code'      => 2,
                        'message'   => get_reason()
                    )
                );
            }
        }
        else {

            $response = array(
                'error' => array(
                    'code'      => $pelecardResponse->getCode(),
                    'message'   => get_reason($pelecardRequest)
                )
            );

        }

        echo json_encode($response);
    }
    catch(Exception $e) {

        $response = array(
            'error' => array(
                'code'      => 2,
                'message'   => get_reason()
            )
        );

        echo json_encode($error);
    }
}

function send_mail($to, $subject, $from, $name, $message) {

    $headers  = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type: text/html; charset=utf-8" . "\r\n";
    $headers .= "From: " . $name . " <" . $from . ">" . "\r\n";
    $headers .= "Reply-To: " . $from . "\r\n";

    return @wp_mail($to, $subject, $message, $headers);
}
