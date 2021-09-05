<?php
/**
 * @file
 * Contains \Drupal\sph_product\Plugin\Block\QRCodeBlock.
 */

namespace Drupal\sph_product\Plugin\Block;

use Drupal\Core\Block\BlockBase;

require __DIR__ . '/../../../vendor/autoload.php';

use Endroid\QrCode\Color\Color;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel\ErrorCorrectionLevelLow;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Label\Label;
use Endroid\QrCode\Logo\Logo;
use Endroid\QrCode\RoundBlockSizeMode\RoundBlockSizeModeMargin;
use Endroid\QrCode\Writer\PngWriter;

/**
 * Provides a 'qrcode' block.
 *
 * @Block(
 *   id = "qrcode_block",
 *   admin_label = @Translation("Product QR Code block"),
 *   category = @Translation("Custom Block")
 * )
 */

class QRCodeBlock extends BlockBase {

	/**
   	* {@inheritdoc}
   	*/

   	public function build() {

		$current_path = \Drupal::request()->getPathInfo();
		$path_args = explode('/', $current_path);

   		$module_handler = \Drupal::service('module_handler');
		$module_path = $module_handler->getModule('sph_product')->getPath();

    	$writer = new PngWriter();

		// Create QR code
		$qrCode = QrCode::create($current_path)
		    ->setEncoding(new Encoding('UTF-8'))
		    ->setErrorCorrectionLevel(new ErrorCorrectionLevelLow())
		    ->setSize(300)
		    ->setMargin(10)
		    ->setRoundBlockSizeMode(new RoundBlockSizeModeMargin())
		    ->setForegroundColor(new Color(0, 0, 0))
		    ->setBackgroundColor(new Color(255, 255, 255));

		$result = $writer->write($qrCode, NULL, NULL);
		//$dataUri = $result->getDataUri();
		$result->saveToFile($module_path .'/images/qrcode.png');

	    return array(
	      '#type' => 'markup',
	      //'#markup' => '<img src="'. $dataUri .'" alt="Purchase QRCode" width="64" height="24">',
	      '#markup' => '<p>To Purchase this product on our app to avail execlusive app-only</p><img src="'. file_create_url($module_path .'/images/qrcode.png') .'" alt="Purchase QRCode" width="220" height="185">',
	    );
  	}

}