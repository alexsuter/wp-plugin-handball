<?php
class HandballMetaBoxGallery
{
    public static function render($post)
    {
        $date = get_post_meta($post->ID, 'handball_gallery_date', true);

        self::renderDate('Datum', 'handball_gallery_date', $date);

        ?>
        <script>
			function handballDateChanged(key) {
				var day = handballValueFromSelect(key + "_day");
				var month = handballValueFromSelect(key + "_month") - 1;
				var year = handballValueFromSelect(key + "_year");
				var hours = 6;
				var minutes = 0;
				var seconds = 0;
				var milliseconds = 0;
				var date = new Date(year, month, day, hours, minutes, seconds, milliseconds);
				var timestamp = date.getTime() / 1000;
				document.getElementById(key).value = timestamp;
			}

			function handballValueFromSelect(id) {
				var e = document.getElementById(id);
				return e.options[e.selectedIndex].value;
			}
        </script>
        <?php
    }

    private static function renderDate($name, $key, $value)
    {
        if (empty($value)) {
            $value = time();
        }
        $day = date('j', $value);
        $month = date('n', $value);
        $year = date('Y', $value);
        ?>
        <label for="<?= $key?>"><?= $name ?></label>
        <br />
        <input name="<?= $key?>" id="<?= $key ?>" type="hidden" value="<?= $value ?>"></input>

		<?php
		self::renderDay($key, $day);
		echo '.';
		self::renderMonth($key, $month);
		echo '.';
		self::renderYear($key, $year);
    }

    private static function renderDay($key, $value) {
        self::renderSelect(1, 31, $key, 'day', $value, 45);
    }

    private static function renderMonth($key, $value) {
        self::renderSelect(1, 12, $key, 'month', $value, 45);
    }

    private static function renderYear($key, $value) {
        self::renderSelect(2000, 2050, $key, 'year', $value, 60);
    }

    private static function renderSelect($from, $to, $key, $subkey, $value, $px) {
        $options = [];
        for ($i = $from; $i <= $to; $i++) {
            $options[] = self::createOption($i, $value);
        }
        ?>
        <select onchange="handballDateChanged('<?= $key ?>')" style="width:<?= $px ?>px;" id="<?= $key . '_' . $subkey ?>" >
        	<?= implode('', $options) ?>
        </select>
		<?php
    }

    private static function createOption($value, $selectedValue) {
        $selected = selected($selectedValue, $value, false);
        return '<option '.$selected.' value="'.$value.'">'.$value.'</option>';
    }

}

