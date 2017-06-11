<?php
class HandballMetaBoxEvent
{
    public static function render($post)
    {
        $startDateTime = get_post_meta($post->ID, 'handball_event_start_datetime', true);
        if (empty($startDateTime) && isset($_GET['handball_event_start_datetime'])) {
            $startDateTime= $_GET['handball_event_start_datetime'];
        }

        $endDateTime = get_post_meta($post->ID, 'handball_event_end_datetime', true);
        if (empty($endDateTime) && isset($_GET['handball_event_end_datetime'])) {
            $endDateTime= $_GET['handball_event_end_datetime'];
        }

        self::renderDateTime('Start', 'handball_event_start_datetime', $startDateTime);
        echo '<br />';
        self::renderDateTime('Ende', 'handball_event_end_datetime', $endDateTime);

        ?>
        <script>
			function handballDateTimeChanged(key) {
				var day = handballValueFromSelect(key + "_day");
				var month = handballValueFromSelect(key + "_month") - 1;
				var year = handballValueFromSelect(key + "_year");
				var hours = handballValueFromSelect(key + "_hour");
				var minutes = handballValueFromSelect(key + "_minute");
				var seconds = 0;
				var milliseconds = 0;
				var date = new Date(year, month, day, hours, minutes, seconds, milliseconds);
				date.setUTCHours(hours);
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

    private static function renderDateTime($name, $key, $value)
    {
        if (empty($value)) {
            $value = time();
        }
        $day = date('j', $value);
        $month = date('n', $value);
        $year = date('Y', $value);

        $hour = date('G', $value);
        $minute = intval(date('i', $value));
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
		echo '&ensp;&ensp;';
		self::renderHour($key, $hour);
		echo ':';
		self::renderMinute($key, $minute);
		echo 'Uhr';
    }

    private static function renderDay($key, $value) {
        self::renderSelect(1, 12, $key, 'day', $value, 45);
    }

    private static function renderMonth($key, $value) {
        self::renderSelect(1, 12, $key, 'month', $value, 45);
    }

    private static function renderYear($key, $value) {
        self::renderSelect(2000, 2050, $key, 'year', $value, 60);
    }

    private static function renderHour($key, $value) {
        self::renderSelect(0, 23, $key, 'hour', $value, 45);
    }

    private static function renderMinute($key, $value) {
        self::renderSelect(0, 59, $key, 'minute', $value, 45);
    }

    private static function renderSelect($from, $to, $key, $subkey, $value, $px) {
        $options = [];
        for ($i = $from; $i <= $to; $i++) {
            $options[] = self::createOption($i, $value);
        }
        ?>
        <select onchange="handballDateTimeChanged('<?= $key ?>')" style="width:<?= $px ?>px;" id="<?= $key . '_' . $subkey ?>" >
        	<?= implode('', $options) ?>
        </select>
		<?php
    }

    private static function createOption($value, $selectedValue) {
        $selected = selected($selectedValue, $value, false);
        return '<option '.$selected.' value="'.$value.'">'.$value.'</option>';
    }

}

