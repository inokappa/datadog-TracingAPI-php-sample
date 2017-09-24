<?php

require_once __DIR__ . "/dd_trace.php";

function ddtrace() {
    $service_name = basename(__FILE__);
    $type = "web";
    $trace_id = rand();
    $trace = new DdTrace('http://127.0.0.1:8126/v0.3', $service_name, $type, $trace_id);

    return $trace;
}

function durationCalculation($startTime) {
    return round(microtime(true), 4) - $startTime;
    return $durationTime;
}

function step_1_sleep($trace, $parent_span_id) {
    $startTime = round(microtime(true), 4);

    $rand_sleep_time = rand(1000000,2000000);
    usleep($rand_sleep_time);

    $span_id = rand();
    $durationTime = durationCalculation($startTime);
    $span = $trace->generateSpandata('step_one_sleep', $span_id, $parent_span_id, $startTime, $durationTime);
    return $span;
}

function step_2_sleep($trace, $parent_span_id) {
    $startTime = round(microtime(true), 4);

    $rand_sleep_time = rand(1500000,3000000);
    usleep($rand_sleep_time);;

    $span_id = rand();
    $durationTime = durationCalculation($startTime);
    $span = $trace->generateSpandata('step_two_sleep', $span_id, $parent_span_id, $startTime, $durationTime);
    return $span;
}

function root() {
   $trace = ddtrace();

   $startTime = round(microtime(true), 4);
   $span_id = rand();
   $spans = [];

   $step_1_span = step_1_sleep($trace, $span_id);
   array_push($spans, $step_1_span);
   $step_2_span = step_2_sleep($trace, $span_id);
   array_push($spans, $step_2_span);

   $durationTime = durationCalculation($startTime);
   $root_span = $trace->generateSpandata('root_sleep', $span_id, $parent_span_id = NULL, $startTime, $durationTime);
   array_push($spans, $root_span);

   $result = $trace->finish($spans);
}

root();
?>
