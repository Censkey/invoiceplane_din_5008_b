<!DOCTYPE html>
<html lang="<?php _trans('cldr'); ?>">
<head>
    <meta charset="utf-8">
    <title><?php _trans('quote'); ?></title>
    <link rel="stylesheet"
          href="<?php echo base_url(); ?>assets/<?php echo get_setting('system_theme', 'invoiceplane'); ?>/css/templates.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/core/css/custom-pdf.css">
    <style>
/* general font size */
body {
  font-size: 12px;
}

/* color of quote Heading fixed */
.quote-title {
    color: #008143;
    font-size: 18px;
}

/* left space due to DIN 5008 2.5mm */
main, footer {
    margin-left: 2.5mm;
}

/* reduces padding in item-table */
table.item-table td {
    padding: 5px 10px;
}

/* defines german DIN_5008_Form_B sichtbriefumschlag position */
#client {
    position: absolute; 
    top: 56mm;
    left: 20mm;
    width: 90mm;
}

/* reduced font-size for items */
table.item-table {
  font-size: 11px !important;
}

/* sender in sichtfenster */
#sichtfenster-absender {
  font-size: 8px;
}

/** Pfalzmarken DIN 5008 Form B */
/* 105mm from top with offset */
#pm-1{
    position: absolute;
    top: 101.8mm;
    left: 5mm;
    width: 5mm;
}
/* 148.5mm from top with offset */
#pm-2{
    position: absolute;
    top: 145.3mm;
    left: 5mm;
    width: 7mm;
}
/* 210mm from top with offset */
#pm-3{
    position: absolute;
    top: 206.8mm;
    left: 5mm;
    width: 5mm;
}
    </style>
</head>

<body>

<div id="pm-1"><hr /></div>
<div id="pm-2"><hr /></div>
<div id="pm-3"><hr /></div>

<div id="client">
	<div id="sichtfenster-absender">
		<?php 
		   echo htmlsc($quote->user_name) . ' - ' . htmlsc($quote->user_address_1) . ' - ' . htmlsc($quote->user_zip) . ' ' . htmlsc($quote->user_city); 
		?>
		<hr />
	</div>
	<div>
		<b><?php _htmlsc(format_client($quote)); ?></b>
	</div>
	<?php if ($quote->client_vat_id) {
		echo '<div>' . trans('vat_id_short') . ': ' . $quote->client_vat_id . '</div>';
	}
	if ($quote->client_tax_code) {
		echo '<div>' . trans('tax_code_short') . ': ' . $quote->client_tax_code . '</div>';
	}
	if ($quote->client_address_1) {
		echo '<div>' . htmlsc($quote->client_address_1) . '</div>';
	}
	if ($quote->client_address_2) {
		echo '<div>' . htmlsc($quote->client_address_2) . '</div>';
	}
	if ($quote->client_city || $quote->client_state || $quote->client_zip) {
		echo '<div>';
        if ($quote->client_zip) {
			echo htmlsc($quote->client_zip) . ' ';
		}
		if ($quote->client_city) {
			echo htmlsc($quote->client_city) . ' ';
		}
		if ($quote->client_state) {
			echo htmlsc($quote->client_state);
		}
		echo '</div>';
	}
	if ($quote->client_country) {
		echo '<div>' . get_country_name(trans('cldr'), $quote->client_country) . '</div>';
	}

	echo '<br/>';

	if ($quote->client_phone) {
		echo '<div>' . trans('phone_abbr') . ': ' . htmlsc($quote->client_phone) . '</div>';
	} ?>
</div>

<header class="clearfix">

    <div id="logo">
        <?php echo invoice_logo_pdf(); ?>
    </div>

	<!-- client is extracted due to absolute position -->

    <div id="company">
        <div><b><?php _htmlsc($quote->user_name); ?></b></div>
        <?php if ($quote->user_vat_id) {
            echo '<div>' . trans('vat_id_short') . ': ' . $quote->user_vat_id . '</div>';
        }
        if ($quote->user_tax_code) {
            echo '<div>' . trans('tax_code_short') . ': ' . $quote->user_tax_code . '</div>';
        }
        if ($quote->user_address_1) {
            echo '<div>' . htmlsc($quote->user_address_1) . '</div>';
        }
        if ($quote->user_address_2) {
            echo '<div>' . htmlsc($quote->user_address_2) . '</div>';
        }
        if ($quote->user_city || $quote->user_state || $quote->user_zip) {
            echo '<div>';
            if ($quote->user_zip) {
                echo htmlsc($quote->user_zip) . ' ';
            }
            if ($quote->user_city) {
                echo htmlsc($quote->user_city) . ' ';
            }
            if ($quote->user_state) {
                echo htmlsc($quote->user_state);
            }
            echo '</div>';
        }
        if ($quote->user_country) {
            echo '<div>' . get_country_name(trans('cldr'), $quote->user_country) . '</div>';
        }

        echo '<br/>';

        if ($quote->user_phone) {
            echo '<div>' . trans('phone_abbr') . ': ' . htmlsc($quote->user_phone) . '</div>';
        }
        if ($quote->user_fax) {
            echo '<div>' . trans('fax_abbr') . ': ' . htmlsc($quote->user_fax) . '</div>';
        }
        ?>
    </div>

</header>

<main>

    <div class="invoice-details clearfix">
        <table>
            <tr>
                <td><?php echo trans('quote_date') . ':'; ?></td>
                <td><?php echo date_from_mysql($quote->quote_date_created, true); ?></td>
            </tr>
        </table>
    </div>

    <h1 class="quote-title"><?php echo trans('quote') . ' ' . $quote->quote_number; ?></h1>

    <table class="item-table">
        <thead>
        <tr>
            <th class="item-name"><?php _trans('item'); ?></th>
            <th class="item-desc"><?php _trans('description'); ?></th>
            <th class="item-amount text-right"><?php _trans('qty'); ?></th>
        </tr>
        </thead>
        <tbody>

        <?php
        foreach ($items as $item) { ?>
            <tr>
                <td><?php _htmlsc($item->item_name); ?></td>
                <td><?php echo nl2br(htmlsc($item->item_description)); ?></td>
                <td class="text-right">
                    <?php echo format_amount($item->item_quantity); ?>
                    <?php if ($item->item_product_unit) : ?>
                        <br>
                        <small><?php _htmlsc($item->item_product_unit); ?></small>
                    <?php endif; ?>
                </td>
            </tr>
        <?php } ?>

        </tbody>
       
    </table>

</main>

<footer>
    <?php if ($quote->notes) : ?>
        <div class="notes">
            <b><?php _trans('notes'); ?></b><br/>
            <?php echo nl2br(htmlsc($quote->notes)); ?>
        </div>
    <?php endif; ?>
</footer>

</body>
</html>
