<!DOCTYPE html>
<html lang="<?php _trans('cldr'); ?>">
<head>
    <meta charset="utf-8">
    <title><?php _trans('invoice'); ?></title>
    <link rel="stylesheet"
          href="<?php echo base_url(); ?>assets/<?php echo get_setting('system_theme', 'invoiceplane'); ?>/css/templates.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/core/css/custom-pdf.css">
    <style>
/* general font size */
body {
  font-size: 12px;
}

/* color of Invoice Heading fixed */
.invoice-title {
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
  font-size: 12px !important;
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
		   echo htmlsc($invoice->user_name) . ' - ' . htmlsc($invoice->user_address_1) . ' - ' . htmlsc($invoice->user_zip) . ' ' . htmlsc($invoice->user_city); 
		?>
		<hr />
	</div>
	<div>
		<b><?php _htmlsc(format_client($invoice)); ?></b>
	</div>
	<?php 
	if ($invoice->client_address_1) {
		echo '<div>' . htmlsc($invoice->client_address_1) . '</div>';
	}
	if ($invoice->client_address_2) {
		echo '<div>' . htmlsc($invoice->client_address_2) . '</div>';
	}
	if ($invoice->client_city || $invoice->client_state || $invoice->client_zip) {
		echo '<div>';
        if ($invoice->client_zip) {
			echo htmlsc($invoice->client_zip) . ' ';
		}
		if ($invoice->client_city) {
			echo htmlsc($invoice->client_city) . ' ';
		}
		if ($invoice->client_state) {
			echo htmlsc($invoice->client_state);
		}
		echo '</div>';
	}
	if ($invoice->client_country) {
		echo '<div>' . get_country_name(trans('cldr'), $invoice->client_country) . '</div>';
	}

	echo '<br/>';

	if ($invoice->client_phone) {
		echo '<div>' . trans('phone_abbr') . ': ' . htmlsc($invoice->client_phone) . '</div>';
	} 
	if ($invoice->client_vat_id) {
		echo '<div>' . trans('vat_id_short') . ': ' . $invoice->client_vat_id . '</div>';
	}
	if ($invoice->client_tax_code) {
		echo '<div>' . trans('tax_code_short') . ': ' . $invoice->client_tax_code . '</div>';
	}	
	?>
</div>

<header class="clearfix">

    <div id="logo">
        <?php echo invoice_logo_pdf(); ?>
    </div>

	<!-- client is extracted due to absolute position -->

    <div id="company">
        <div><b><?php _htmlsc($invoice->user_name); ?></b></div>
        <?php if ($invoice->user_vat_id) {
            echo '<div>' . trans('vat_id_short') . ': ' . $invoice->user_vat_id . '</div>';
        }
        if ($invoice->user_tax_code) {
            echo '<div>' . trans('tax_code_short') . ': ' . $invoice->user_tax_code . '</div>';
        }
        if ($invoice->user_address_1) {
            echo '<div>' . htmlsc($invoice->user_address_1) . '</div>';
        }
        if ($invoice->user_address_2) {
            echo '<div>' . htmlsc($invoice->user_address_2) . '</div>';
        }
        if ($invoice->user_city || $invoice->user_state || $invoice->user_zip) {
            echo '<div>';
            if ($invoice->user_zip) {
                echo htmlsc($invoice->user_zip) . ' ';
            }
            if ($invoice->user_city) {
                echo htmlsc($invoice->user_city) . ' ';
            }
            if ($invoice->user_state) {
                echo htmlsc($invoice->user_state);
            }
            echo '</div>';
        }
        if ($invoice->user_country) {
            echo '<div>' . get_country_name(trans('cldr'), $invoice->user_country) . '</div>';
        }

        echo '<br/>';

        if ($invoice->user_phone) {
            echo '<div>' . trans('phone_abbr') . ': ' . htmlsc($invoice->user_phone) . '</div>';
        }
        if ($invoice->user_fax) {
            echo '<div>' . trans('fax_abbr') . ': ' . htmlsc($invoice->user_fax) . '</div>';
        }
        ?>
    </div>

</header>

<main>

<div class="invoice-details clearfix">
    <table>
        <tr>
            <td><?php echo trans('invoice_date') . ':'; ?></td>
            <td><?php echo date_from_mysql($invoice->invoice_date_created, true); ?></td>
        </tr>
	            <?php if (!empty($custom_fields['invoice']['Bestellnummer'])): ?>
            <tr>
                <td><?php echo ('Bestellnummer') . ':'; ?></td>
                <td><?php echo $custom_fields['invoice']['Bestellnummer']; ?></td>
            </tr>
        <?php endif; ?>
        <?php if (!empty($custom_fields['invoice']['Lieferscheinnummer'])): ?>
            <tr>
                <td><?php echo ('Lieferscheinnummer') . ':'; ?></td>
                <td><?php echo $custom_fields['invoice']['Lieferscheinnummer']; ?></td>
            </tr>
        <?php endif; ?>
    </table>
</div>

    <h1 class="invoice-title"><?php echo trans('invoice') . ' ' . $invoice->invoice_number; ?></h1>

    <table class="item-table">
        <thead>
        <tr>
            <th class="item-name"><?php _trans('item'); ?></th>
            <th class="item-desc"><?php _trans('description'); ?></th>
            <th class="item-amount text-right"><?php _trans('qty'); ?></th>
            <th class="item-price text-right"><?php echo 'Einzelpreis'; ?></th>
	    <th class="item-taxrate"><?php echo 'USt Satz'; ?></th>
            <?php if ($show_item_discounts) : ?>
                <th class="item-discount text-right"><?php _trans('discount'); ?></th>
            <?php endif; ?>
            <th class="item-total text-right"><?php echo 'Nettosumme'; ?></th>
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
                        <?php _htmlsc($item->item_product_unit); ?>
                    <?php endif; ?>
                </td>
                <td class="text-right">
                    <?php echo format_currency($item->item_price); ?>
                </td>
		<td class="text-right">
                    <?php echo intval($item->item_tax_rate_percent).'%'; ?>
                </td>
                <?php if ($show_item_discounts) : ?>
                    <td class="text-right">
                        <?php echo format_currency($item->item_discount); ?>
                    </td>
                <?php endif; ?>
                <td class="text-right">
                    <?php echo format_currency(($item->item_total)-($item->item_tax_total)); ?>
                </td>
            </tr>
        <?php } ?>

        </tbody>
        <tbody class="invoice-sums">

        <tr>
            <td <?php echo($show_item_discounts ? 'colspan="6"' : 'colspan="5"'); ?> class="text-right">
                <?php echo 'Nettosumme'; ?>
            </td>
            <td class="text-right"><?php echo format_currency($invoice->invoice_item_subtotal); ?></td>
        </tr>

         <?php
        $taxarray2 = array();
        $linecounter = 0;
        foreach ($items as $item) { ?>
        <?php
                      // load all tax_rates/mdl_tax_rates and calc subtotal per rate
                      //$this->load->model('tax_rates/mdl_tax_rates');
                      //$taxarray1 = $this->mdl_tax_rates->where('tax_rate_id', $item->item_tax_rate_id)->get()->row();                                 
                      //$taxarray2[$item->item_tax_rate_id]->taxcode = $taxarray1->tax_rate_code;
                      if (!isset($taxarray2[$item->item_tax_rate_id]))
                            $taxarray2[$item->item_tax_rate_id]=array();
                      $taxarray2[$item->item_tax_rate_id]['taxdescr'] = $item->item_tax_rate_name;
                      $taxarray2[$item->item_tax_rate_id]['taxperc'] = $item->item_tax_rate_percent;
                      if (isset($taxarray2[$item->item_tax_rate_id]['netamount']))
                            $taxarray2[$item->item_tax_rate_id]['netamount'] += ($item->item_subtotal - $item->item_discount);
                      else
                            $taxarray2[$item->item_tax_rate_id]['netamount'] = ($item->item_subtotal - $item->item_discount);
                      if (isset($taxarray2[$item->item_tax_rate_id]['taxamount'])) 
                            $taxarray2[$item->item_tax_rate_id]['taxamount'] += $item->item_tax_total;
                      else
                            $taxarray2[$item->item_tax_rate_id]['taxamount'] = $item->item_tax_total;
                ?>
        <?php } ?>

        <?php if ($invoice->invoice_item_tax_total > 0) {
            foreach($taxarray2 as $key => $value) :?>
                <tr>
                    <td <?php echo($show_item_discounts ? 'colspan="5"' : 'colspan="4"'); ?> class="text-right"></td>
                    <td class="text-right"><?php echo 'USt '. intval(format_amount($value['taxperc'])).'%'; ?></>
                    </td>
                    <td class="text-right"><?php echo format_currency($value['taxamount']); ?></td>
                </tr>
        <?php endforeach ?>
        <!--<tr> Steuer Summe
                <td class="text-right"></td>
                <td <?php echo($show_item_discounts ? 'colspan="5"' : 'colspan="4"'); ?> class="text-right">
                    <?php _trans('item_tax'); ?>
                </td>
                <td class="text-right">
                    <?php echo format_currency($invoice->invoice_item_tax_total);?>
                </td>
            </tr>-->
       <?php } ?>

        <?php foreach ($invoice_tax_rates as $invoice_tax_rate) : ?>
            <tr>
                <td <?php echo($show_item_discounts ? 'colspan="6"' : 'colspan="5"'); ?> class="text-right">
                    <?php echo htmlsc($invoice_tax_rate->invoice_tax_rate_name) . ' (' . format_amount($invoice_tax_rate->invoice_tax_rate_percent) . '%)'; ?>
                </td>
                <td class="text-right">
                    <?php echo format_currency($invoice_tax_rate->invoice_tax_rate_amount); ?>
                </td>
            </tr>
        <?php endforeach ?>

        <?php if ($invoice->invoice_discount_percent != '0.00') : ?>
            <tr>
                <td <?php echo($show_item_discounts ? 'colspan="6"' : 'colspan="5"'); ?> class="text-right">
                    <?php _trans('discount'); ?>
                </td>
                <td class="text-right">
                    <?php echo format_amount($invoice->invoice_discount_percent); ?>%
                </td>
            </tr>
        <?php endif; ?>
        <?php if ($invoice->invoice_discount_amount != '0.00') : ?>
            <tr>
                <td <?php echo($show_item_discounts ? 'colspan="6"' : 'colspan="5"'); ?> class="text-right">
                    <?php _trans('discount'); ?>
                </td>
                <td class="text-right">
                    <?php echo format_currency($invoice->invoice_discount_amount); ?>
                </td>
            </tr>
        <?php endif; ?>

        <tr>
            <td <?php echo($show_item_discounts ? 'colspan="6"' : 'colspan="5"'); ?> class="text-right">
                <b><?php _trans('total'); ?></b>
            </td>
            <td class="text-right">
                <b><?php echo format_currency($invoice->invoice_total); ?></b>
            </td>
        </tr>
        </tbody>
    </table>

</main>

<footer>
    <?php if ($invoice->invoice_terms) : ?>
        <div class="notes">
            <?php echo nl2br(htmlsc($invoice->invoice_terms)); ?>
        </div>
    <?php endif; ?>
</footer>

</body>
</html>
