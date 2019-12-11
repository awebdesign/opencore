<?php

namespace AwebCore\App\Http\Controllers\Admin;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

use Illuminate\Support\Facades\DB;
use AwebCore\App\Http\Controllers\Controller;

class AdminExampleController extends Controller
{
    function index() {
        echo "AdminExampleController HERE";
        return response("AdminExampleController HERE");
        
        $start = microtime(true);

        DB::enableQueryLog(); //connection()->

        $query = "SELECT DISTINCT *, pd.name AS name, p.image, m.name AS manufacturer, (SELECT price FROM product_discount pd2 WHERE pd2.product_id = p.product_id AND pd2.customer_group_id = '1' AND pd2.quantity = '1' AND ((pd2.date_start = '0000-00-00' OR pd2.date_start < NOW()) AND (pd2.date_end = '0000-00-00' OR pd2.date_end > NOW())) ORDER BY pd2.priority ASC, pd2.price ASC LIMIT 1) AS discount, (SELECT price FROM product_special ps WHERE ps.product_id = p.product_id AND ps.customer_group_id = '1' AND ((ps.date_start = '0000-00-00' OR ps.date_start < NOW()) AND (ps.date_end = '0000-00-00' OR ps.date_end > NOW())) ORDER BY ps.priority ASC, ps.price ASC LIMIT 1) AS special, (SELECT points FROM product_reward pr WHERE pr.product_id = p.product_id AND pr.customer_group_id = '1') AS reward, (SELECT ss.name FROM stock_status ss WHERE ss.stock_status_id = p.stock_status_id AND ss.language_id = '2') AS stock_status, (SELECT wcd.unit FROM weight_class_description wcd WHERE p.weight_class_id = wcd.weight_class_id AND wcd.language_id = '2') AS weight_class, (SELECT lcd.unit FROM length_class_description lcd WHERE p.length_class_id = lcd.length_class_id AND lcd.language_id = '2') AS length_class, (SELECT AVG(rating) AS total FROM review r1 WHERE r1.product_id = p.product_id AND r1.status = '1' GROUP BY r1.product_id) AS rating, (SELECT COUNT(*) AS total FROM review r2 WHERE r2.product_id = p.product_id AND r2.status = '1' GROUP BY r2.product_id) AS reviews, p.sort_order FROM product p LEFT JOIN product_description pd ON (p.product_id = pd.product_id) LEFT JOIN product_to_store p2s ON (p.product_id = p2s.product_id) LEFT JOIN manufacturer m ON (p.manufacturer_id = m.manufacturer_id) WHERE pd.language_id = '1' AND p.status = '1' AND p.date_available <= NOW() AND p2s.store_id = '0' ORDER BY p.product_id LIMIT 249";
        $query = DB::select($query);

        echo "Results: " . count($query);

        foreach($query as $row) {
            DB::select("SELECT DISTINCT *, pd.name AS name, p.image, m.name AS manufacturer, (SELECT price FROM product_discount pd2 WHERE pd2.product_id = p.product_id AND pd2.customer_group_id = '1' AND pd2.quantity = '1' AND ((pd2.date_start = '0000-00-00' OR pd2.date_start < NOW()) AND (pd2.date_end = '0000-00-00' OR pd2.date_end > NOW())) ORDER BY pd2.priority ASC, pd2.price ASC LIMIT 1) AS discount, (SELECT price FROM product_special ps WHERE ps.product_id = p.product_id AND ps.customer_group_id = '1' AND ((ps.date_start = '0000-00-00' OR ps.date_start < NOW()) AND (ps.date_end = '0000-00-00' OR ps.date_end > NOW())) ORDER BY ps.priority ASC, ps.price ASC LIMIT 1) AS special, (SELECT points FROM product_reward pr WHERE pr.product_id = p.product_id AND customer_group_id = '1') AS reward, (SELECT ss.name FROM stock_status ss WHERE ss.stock_status_id = p.stock_status_id AND ss.language_id = '1') AS stock_status, (SELECT wcd.unit FROM weight_class_description wcd WHERE p.weight_class_id = wcd.weight_class_id AND wcd.language_id = '1') AS weight_class, (SELECT lcd.unit FROM length_class_description lcd WHERE p.length_class_id = lcd.length_class_id AND lcd.language_id = '1') AS length_class, (SELECT AVG(rating) AS total FROM review r1 WHERE r1.product_id = p.product_id AND r1.status = '1' GROUP BY r1.product_id) AS rating, (SELECT COUNT(*) AS total FROM review r2 WHERE r2.product_id = p.product_id AND r2.status = '1' GROUP BY r2.product_id) AS reviews, p.sort_order FROM product p LEFT JOIN product_description pd ON (p.product_id = pd.product_id) LEFT JOIN product_to_store p2s ON (p.product_id = p2s.product_id) LEFT JOIN manufacturer m ON (p.manufacturer_id = m.manufacturer_id) WHERE p.product_id = '{$row->product_id}' AND pd.language_id = '1' AND p.status = '1' AND p.date_available <= NOW() AND p2s.store_id = '0'");
        }

        $diff = microtime(true) - $start;
        $sec = intval($diff);
        $micro = $diff - $sec;

        // Format the result as you want it
        // $final will contain something like "00:00:02.452"
        $final = strftime('%T', mktime(0, 0, $sec)) . str_replace('0.', '.', sprintf('%.3f', $micro));
        echo "<br/><br/>Seconds: {$final}";
        echo "<br/><br/>Queries: " . count(DB::getQueryLog());
        echo "<br/><br/>memory: " . $this->mb_formatted(memory_get_usage()); // 57960
    }

    function mb_formatted($size)
    {
        $units = array( 'B', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB');
        $power = $size > 0 ? floor(log($size, 1024)) : 0;
        return number_format($size / pow(1024, $power), 2, '.', ',') . ' ' . $units[$power];
    }
}
