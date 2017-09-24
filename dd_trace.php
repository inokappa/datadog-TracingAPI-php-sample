<?php
    class DdTrace {
        function __construct($agent_url, $service_name, $type, $trace_id) {
            $this->agentUrl = $agent_url;
            $this->agentEndpoint = $agent_url . "/traces";
            $this->serviceName = $service_name;
            $this->Type = $type;
            $this->traceId = $trace_id;
        }

        public function generateSpandata($span_name, $span_id, $parent_span_id = NULL, $startTime, $durationTime) {
            $data = array(
                'trace_id' => $this->traceId,
                'span_id' => $span_id,
                'name' => $span_name,
                'resource' => $span_name,
                'type' => $this->Type,
                'start' => intval($startTime * 1000000000),
                'duration' => round($durationTime * 1000000000),
            );
            if (! is_null($parent_span_id)) {
                $data['service'] = $this->serviceName . '-' . $span_name;
                $data['parent_id'] = $parent_span_id;
            } else {
                $data['service'] = $this->serviceName;
            }
            return $data;
        }

        public function finish($data) {
            echo json_encode([$data]);
            $endpoint = $this->agentEndpoint;
            $curl = curl_init($endpoint);
            $options = array(
                CURLOPT_HTTPHEADER => array(
                    'Content-Type: application/json',
                ),
                CURLOPT_CUSTOMREQUEST => "PUT",
                CURLOPT_POSTFIELDS => json_encode([$data]),
            );
            curl_setopt_array($curl, $options);
            $result = curl_exec($curl);

            return $result;
        }
    }
?>
