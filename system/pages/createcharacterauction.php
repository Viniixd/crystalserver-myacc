<style>
    form {
        display: block;
        margin-top: 0;
        margin-block-end: 0;
    }

    .CVIcon.CVIconObject img {
        width: 32px;
        height: 32px;
    }

    .CharacterDetailsBlock .ShowMoreOrLess a {
        cursor: pointer;
    }

    .CollapsedBlock .TableContent tr:last-child {
        display: table-row;
        text-align: center;
    }
</style>

<?php
/**
 *
 * Char Bazaar
 *
 */

defined('MYAAC') or die('Direct access not allowed!');
$title = 'Create Auction';

// // =====================================================
// // EXECUTA O PROCESSAMENTO SEMIAUTOMÁTICO DO BAZAAR
require SYSTEM . 'pages/char_bazaar/semifinishauction.php';
// // =====================================================

if ($logged) {
    require SYSTEM . 'pages/char_bazaar/coins_balance.php';
} else {
    if (!empty($errors))
        $twig->display('error_box.html.twig', array('errors' => $errors));

    $twig->display('account.login.html.twig', array(
        'redirect' => isset($_REQUEST['redirect']) ? $_REQUEST['redirect'] : null,
        'account' => USE_ACCOUNT_NAME ? 'Name' : 'Number',
        'account_login_by' => getAccountLoginByLabel(),
        'error' => isset($errors[0]) ? $errors[0] : null
    ));
    return;
}

/* CHAR BAZAAR CONFIG ~ USING IN STEPS, DO NOT REMOVE! */
$charbazaar_create = $config['bazaar_create'];
$charbazaar_tax = $config['bazaar_tax'];
$charbazaar_bid = $config['bazaar_bid'];
$charbazaar_newacc = $config['bazaar_accountid'];
/* CHAR BAZAAR CONFIG END */

$getAuctionStep = $_GET['step'] ?? null;

/* REDIRECT TO STEP 1 */
if (empty($getAuctionStep) || $getAuctionStep < 1 || $getAuctionStep > 5) {
    header('Location: ' . BASE_URL . '?subtopic=createcharacterauction&step=1');
}
/* REDIRECT TO STEP 1 END */

/* STEP 01 START */
if ($getAuctionStep == 1) {
    require SYSTEM . 'pages/char_bazaar/create_step1.php';
}
/* STEP 01 END */

/* STEP 02 START */
if ($getAuctionStep == 2) {
    require SYSTEM . 'pages/char_bazaar/create_step2.php';
}
/* STEP 02 END */

/* STEP 03 START */
if ($getAuctionStep == 3) {
    require SYSTEM . 'pages/char_bazaar/create_step3.php';
}
/* STEP 03 END */

/* STEP 03 START */
if ($getAuctionStep == 4) {
    require SYSTEM . 'pages/char_bazaar/create_step4.php';
}
/* STEP 04 END */

/* STEP CONFIRM START */
if ($getAuctionStep == '5') {
    /* CADASTRAR AUCTION */
    if (isset($_POST['auction_confirm']) && isset($_POST['auction_price']) && isset($_POST['auction_days']) && isset($_POST['auction_character'])) {
        $idLogged = $account_logged->getId(); // Para uso na validação de personagem
        $auction_price =  $_POST['auction_price'];
        $auction_days =  $_POST['auction_days'];
        $auction_character = $_POST['auction_character'];

        // Validação: verifica se o preço e a data estão dentro dos limites
         if ( !is_numeric($auction_price) || !is_numeric($auction_days) || !is_numeric($auction_character) ||
         (int)$auction_price < 1 || (int)$auction_days < 1 || (int)$auction_days > 28) {
            echo <<<HTML
            <div class="SmallBox">
                <div class="MessageContainer">
                    <div class="BoxFrameHorizontal" style="background-image:url(templates/tibiacom/images/global/content/box-frame-horizontal.gif);"></div>
                    <div class="BoxFrameEdgeLeftTop" style="background-image:url(templates/tibiacom/images/global/content/box-frame-edge.gif);"></div>
                    <div class="BoxFrameEdgeRightTop" style="background-image:url(templates/tibiacom/images/global/content/box-frame-edge.gif);"></div>
                    <div class="Message">
                    <div class="BoxFrameVerticalLeft" style="background-image:url(templates/tibiacom/images/global/content/box-frame-vertical.gif);"></div>
                    <div class="BoxFrameVerticalRight" style="background-image:url(templates/tibiacom/images/global/content/box-frame-vertical.gif);"></div>
                    <table class="HintBox">
                        <tbody>
                        <tr>
                        <td>
                            <p style="color: #b32d2d; font-weight: bold; text-align: center; margin: 0;">
                            You must set a valid value for the auction date and price.            </p>
                        </td>
                        </tr>
                        </tbody>
                    </table>
                    </div>
                    <div class="BoxFrameHorizontal" style="background-image:url(templates/tibiacom/images/global/content/box-frame-horizontal.gif);"></div>
                    <div class="BoxFrameEdgeRightBottom" style="background-image:url(templates/tibiacom/images/global/content/box-frame-edge.gif);"></div>
                    <div class="BoxFrameEdgeLeftBottom" style="background-image:url(templates/tibiacom/images/global/content/box-frame-edge.gif);"></div>
                </div>
            </div>
            <br>
            HTML;
            return;
        }

        /* UPDATE CHARACTER TO NEW ACCOUNT */
        /*$update_character = $db->query('UPDATE `players` SET `account_id` = `1` WHERE `id` = ' . $auction_character .'');
        $update_character = $update_character->fetch();*/
        /* UPDATE CHARACTER TO NEW ACCOUNT END */

        /* REGISTER AUCTION */
        $getCharacter = $db->query('SELECT `id`, `account_id` FROM `players` WHERE `id` = ' . $db->quote($auction_character) . '');
        $getCharacter = $getCharacter->fetch();

        // Validação: verifica se a conta logada possui o personagem
        if ($idLogged != $getCharacter['account_id']) {
            echo <<<HTML
            <div class="SmallBox">
                <div class="MessageContainer">
                    <div class="BoxFrameHorizontal" style="background-image:url(templates/tibiacom/images/global/content/box-frame-horizontal.gif);"></div>
                    <div class="BoxFrameEdgeLeftTop" style="background-image:url(templates/tibiacom/images/global/content/box-frame-edge.gif);"></div>
                    <div class="BoxFrameEdgeRightTop" style="background-image:url(templates/tibiacom/images/global/content/box-frame-edge.gif);"></div>
                    <div class="Message">
                    <div class="BoxFrameVerticalLeft" style="background-image:url(templates/tibiacom/images/global/content/box-frame-vertical.gif);"></div>
                    <div class="BoxFrameVerticalRight" style="background-image:url(templates/tibiacom/images/global/content/box-frame-vertical.gif);"></div>
                    <table class="HintBox">
                        <tbody>
                        <tr>
                        <td>
                            <p style="color: #b32d2d; font-weight: bold; text-align: center; margin: 0;">
                            You can only create auctions for your characters.            </p>
                        </td>
                        </tr>
                        </tbody>
                    </table>
                    </div>
                    <div class="BoxFrameHorizontal" style="background-image:url(templates/tibiacom/images/global/content/box-frame-horizontal.gif);"></div>
                    <div class="BoxFrameEdgeRightBottom" style="background-image:url(templates/tibiacom/images/global/content/box-frame-edge.gif);"></div>
                    <div class="BoxFrameEdgeLeftBottom" style="background-image:url(templates/tibiacom/images/global/content/box-frame-edge.gif);"></div>
                </div>
            </div>
            <br>
            HTML;
            return;
        }

        $getAccount = $db->query('SELECT `id`, `premdays`, `coins_transferable` FROM `accounts` WHERE `id` = ' . $db->quote($getCharacter['account_id']) . '');
        $getAccount = $getAccount->fetch();

        if ($auction_days > 28) {
            $auction_inputdays = $auction_days;
            $auction_end = date('Ymd', strtotime('+' . $auction_inputdays . ' days'));
        } else {
            $auction_inputdays = $auction_days;
            $auction_end = date('Ymd', strtotime('+' . $auction_inputdays . ' days'));
        }

        $account_old = $getCharacter['account_id'];
        $account_new = $charbazaar_newacc;
        $player_id = $auction_character;
        $price = $auction_price;

        $date_start = date('YmdHis');
        $date_end = $auction_end . date('His');

        $getCoinsAccountLogged = $db->query('SELECT `id`, `coins_transferable` FROM `accounts` WHERE `id` = ' . $account_logged->getId() . '');
        $getCoinsAccountLogged = $getCoinsAccountLogged->fetch();

        $charbazaar_mycoins = $getCoinsAccountLogged['coins_transferable'];
        $charbazaar_mycoins_calc = $charbazaar_mycoins - $charbazaar_create;

        $auctionId = 0;

        // Começa como zero e é definida como 1 se houver moedas suficientes
        // Essa variável controla a renderização da página de sucesso logo abaixo
        $auctionSucesso = 0;

        if ($getCoinsAccountLogged['coins_transferable'] > $charbazaar_create) {

            $auctionSucesso = 1;
            $update_accountcoins = $db->exec('UPDATE `accounts` SET `coins_transferable` = ' . $charbazaar_mycoins_calc . ' WHERE `id` = ' . $getAccount['id'] . '');

            $insert_auction = $db->exec('INSERT INTO `myaac_charbazaar` 
(`account_old`, `account_new`, `player_id`, `price`, `date_end`, `date_start`, `bid_account`, `bid_price`, `status`)
VALUES (' . $db->quote($account_old) . ', ' . $db->quote($account_new) . ', ' . $db->quote($player_id) . ', ' . $db->quote($price) . ', ' . $db->quote($date_end) . ', ' . $db->quote($date_start) . ', 0, 0, 0)');

            $getAuctionId = $db->query("SELECT `id` FROM `myaac_charbazaar` WHERE `account_old` = " . $db->quote($account_old) . " AND `player_id` = " . $db->quote($player_id) . " ORDER BY `id` DESC LIMIT 1");
			$auctionIdRow = $getAuctionId->fetch();

			$auctionId = $auctionIdRow ? $auctionIdRow['id'] : 0;

            $update_character = $db->exec('UPDATE `players` SET `account_id` = ' . $account_new . ' WHERE `id` = ' . $getCharacter['id'] . '');

            ?>
        <div class="TableContainer">
            <div class="CaptionContainer">
                <div class="CaptionInnerContainer">
                    <span class="CaptionEdgeLeftTop"
                          style="background-image:url(<?= $template_path; ?>/images/global/content/box-frame-edge.gif);"></span>
                    <span class="CaptionEdgeRightTop"
                          style="background-image:url(<?= $template_path; ?>/images/global/content/box-frame-edge.gif);"></span>
                    <span class="CaptionBorderTop"
                          style="background-image:url(<?= $template_path; ?>/images/global/content/table-headline-border.gif);"></span>
                    <span class="CaptionVerticalLeft"
                          style="background-image:url(<?= $template_path; ?>/images/global/content/box-frame-vertical.gif);"></span>
                    <div class="Text">Auction created</div>
                    <span class="CaptionVerticalRight"
                          style="background-image:url(<?= $template_path; ?>/images/global/content/box-frame-vertical.gif);"></span>
                    <span class="CaptionBorderBottom"
                          style="background-image:url(<?= $template_path; ?>/images/global/content/table-headline-border.gif);"></span>
                    <span class="CaptionEdgeLeftBottom"
                          style="background-image:url(<?= $template_path; ?>/images/global/content/box-frame-edge.gif);"></span>
                    <span class="CaptionEdgeRightBottom"
                          style="background-image:url(<?= $template_path; ?>/images/global/content/box-frame-edge.gif);"></span>
                </div>
            </div>
            <table class="Table5" cellspacing="0" cellpadding="0">
                <tbody>
                <tr>
                    <td>
                        <div class="InnerTableContainer">
                            <table style="width:100%;">
                                <tbody>
                                <tr>
                                    <td>
                                        <div class="TableContentContainer">
                                            <table class="TableContent" style="border:1px solid #faf0d7;" width="100%">
                                                <tbody>
                                                <tr>
                                                    <td style="font-weight:normal;"><img
                                                            src="<?= $template_path; ?>/images/charactertrade/confirm.gif">
                                                    </td>
                                                    <td style="font-weight:bold; font-size: 24px;">Auction created</td>
                                                    <td>
                                                        <a href="?subtopic=currentcharactertrades&details=<?= $auctionId ?>">
                                                            <div class="BigButton"
                                                                 style="background-image:url(<?= $template_path; ?>/images/global/buttons/sbutton_green.gif)">
                                                                <div onmouseover="MouseOverBigButton(this);"
                                                                     onmouseout="MouseOutBigButton(this);">
                                                                    <div class="BigButtonOver"
                                                                         style="background-image: url(<?= $template_path; ?>/images/global/buttons/sbutton_green_over.gif); visibility: hidden;"></div>
                                                                    <input name="auction_confirm" class="BigButtonText"
                                                                           type="button" value="View auction"></div>
                                                            </div>
                                                        </a>
                                                    </td>
                                                </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
        <?php
        }

        // Página de aviso de erro na criação do bazar
        else {
            $auctionSucesso = 0;
        ?>
        <div class="TableContainer">
            <div class="CaptionContainer">
                <div class="CaptionInnerContainer">
                    <span class="CaptionEdgeLeftTop"
                          style="background-image:url(<?= $template_path; ?>/images/global/content/box-frame-edge.gif);"></span>
                    <span class="CaptionEdgeRightTop"
                          style="background-image:url(<?= $template_path; ?>/images/global/content/box-frame-edge.gif);"></span>
                    <span class="CaptionBorderTop"
                          style="background-image:url(<?= $template_path; ?>/images/global/content/table-headline-border.gif);"></span>
                    <span class="CaptionVerticalLeft"
                          style="background-image:url(<?= $template_path; ?>/images/global/content/box-frame-vertical.gif);"></span>
                    <div class="Text">Atenção!</div>
                    <span class="CaptionVerticalRight"
                          style="background-image:url(<?= $template_path; ?>/images/global/content/box-frame-vertical.gif);"></span>
                    <span class="CaptionBorderBottom"
                          style="background-image:url(<?= $template_path; ?>/images/global/content/table-headline-border.gif);"></span>
                    <span class="CaptionEdgeLeftBottom"
                          style="background-image:url(<?= $template_path; ?>/images/global/content/box-frame-edge.gif);"></span>
                    <span class="CaptionEdgeRightBottom"
                          style="background-image:url(<?= $template_path; ?>/images/global/content/box-frame-edge.gif);"></span>
                </div>
            </div>
            <table class="Table5" cellspacing="0" cellpadding="0">
                <tbody>
                <tr>
                    <td>
                        <div class="InnerTableContainer">
                            <table style="width:100%;">
                                <tbody>
                                <tr>
                                    <td>
                                        <div class="TableContentContainer">
                                            <table class="TableContent" style="border:1px solid #faf0d7;" width="100%">
                                                <tbody>
                                                <tr>
                                                    <td style="font-weight:normal;"><img
                                                            src="<?= $template_path; ?>/images/charactertrade/nocoins.gif">
                                                    </td>
                                                    <td style="font-weight:bold; font-size: 24px;">Coins Insuficientes!</td>
                                                    <td>
                                                    <a href="?subtopic=createcharacterauction&step=1">
                                                        <div class="BigButton"
                                                            style="background-image:url(<?= $template_path; ?>/images/global/buttons/sbutton.gif)">
                                                            <div onmouseover="MouseOverBigButton(this);" onmouseout="MouseOutBigButton(this);">
                                                                <div class="BigButtonOver"
                                                                    style="background-image: url(<?= $template_path; ?>/images/global/buttons/sbutton_over.gif); visibility: hidden;"></div>
                                                                <input class="BigButtonText" type="button" value="Back">
                                                            </div>
                                                        </div>
                                                    </a>
                                                    </td>
                                                </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
        <?php
        }
    }
    /* CADASTRAR AUCTION END */
}
/* STEP CONFIRM END */
?>