<?php

namespace Cksaa\LaravelAliMts;

use function Couchbase\defaultDecoder;
use Illuminate\Http\Request;
use AlibabaCloud\Client\AlibabaCloud;
use AlibabaCloud\Client\Exception\ClientException;
use AlibabaCloud\Client\Exception\ServerException;

class Mts
{
    private $endPoint;
    private $region;
    private $bucket;
    private $location;
    private $input;

    public function __construct()
    {
        $accessKeyId = config('mts.access_key_id');
        $accessSecret = config('mts.access_key_secret');
        $this->region = config('mts.region');
        $this->bucket = config('mts.bucket');
        $this->location = config('mts.oss_location');

        $this->endPoint = AlibabaCloud::resolveHost('Mts', $this->region);

        AlibabaCloud::accessKeyClient($accessKeyId, $accessSecret)->regionId($this->region)->asDefaultClient();
    }

    private function setInput($file)
    {
        $this->input = json_encode([
            'Location' => $this->location,
            'Bucket' => $this->bucket,
            'Object' => urlencode($file)
        ]);
    }

    /**
     * 提交转码作业 https://help.aliyun.com/document_detail/29226.html
     * @param string $file 文件名
     * @param  $outputConfig
     * @return array
     */
    public function submitJobs($file, $outputConfig)
    {
        $action = 'SubmitJobs';

        $this->setInput($file);

        $params = [
            'Input' => $this->input,
            'OutputBucket' => $this->bucket,
            'OutputLocation' => $this->location,
            'Outputs' => $outputConfig,
            'PipelineId' => config('mts.pipeline_id'),
        ];

        return $this->sendRequest($action, $params);
    }

    /**
     * 查询转码作业 https://help.aliyun.com/document_detail/29228.html
     * @param $jobIds
     * @return array
     */
    public function queryJobList($jobIds)
    {
        $action = 'QueryJobList';

        return $this->sendRequest($action, ['JobIds' => $jobIds]);
    }

    /**
     * 查询截图作业 https://help.aliyun.com/document_detail/29233.html
     * @param $jobIds
     * @return array
     */
    public function querySnapshotJobList($jobIds)
    {
        $action = 'QuerySnapshotJobList';

        $response = $this->sendRequest($action, ['SnapshotJobIds' => $jobIds]);

        if(! $response['state']){
            return $response;
        }

        $data = $response['data']['SnapshotJobList']['SnapshotJob'][0];

        $outputFile = $data['SnapshotConfig']['OutputFile'];
        $outputFile['Object'] = urldecode($outputFile['Object']);

        $outputFiles = [];
        $i=1;
        while ($i<=$data['Count']){
            $outputFiles[] = str_replace('{Count}', str_pad($i, 5, 0, STR_PAD_LEFT), $outputFile['Object']);
            $i++;
        }

        return ['state' => true, 'data' => $outputFiles];
    }

    /**
     * 提交媒体信息作业 https://help.aliyun.com/document_detail/29220.html
     * @param string $file 文件路径
     * @param string $userData
     * @return array
     */
    public function submitMediaInfoJob($file, $userData = '')
    {
        $action = 'SubmitMediaInfoJob';

        $this->setInput($file);

        $params = ['Input' => $this->input];

        if(! empty($userData)){
            $params['UserData'] = $userData;
        }

        return $this->sendRequest($action, $params);
    }

    protected function sendRequest($action, $params)
    {
        try {
            $options = array_merge(['RegionId' => $this->region], $params);

            $result = AlibabaCloud::rpc()
                ->product('Mts')
                // ->scheme('https') // https | http
                ->version('2014-06-18')
                ->action($action)
                ->method('POST')
                ->host($this->endPoint)
                ->options([
                    'query' => $options,
                ])
                ->request();
        } catch (ClientException $e) {
            return ['state' => false, 'msg' => $e->getErrorMessage()];
        } catch (ServerException $e) {
            return ['state' => false, 'msg' => $e->getErrorMessage()];
        }

        return ['state' => true, 'data' => $result->toArray()];
    }
}