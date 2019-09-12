<h1 align="center"> laravel-ali-mts </h1>

<p align="center"> .</p>


## Installing

```shell
$ composer require cksaa/laravel-ali-mts -vvv
```
```shell
$ php artisan vendor:publish --provider="Cksaa\LaravelAliMts\MtsServiceProvider"
```

## Usage
```php
use Cksaa\LaravelAliMts\Mts;

//查询截图结果
$mts->querySnapshotJobList($jobIds);
/**
输出{
    "state": true,
    "data": [
        "e091c1aaef4f4525934e0224a7132d1f/0/00001.jpg",
        "e091c1aaef4f4525934e0224a7132d1f/0/00002.jpg",
        "e091c1aaef4f4525934e0224a7132d1f/0/00003.jpg"
    ]
}
*/

//查询转码结果
$mts->queryJobList($message->jobId);
/**
{"state":true,"data":{"JobList":{"Job":[{"CreationTime":"2019-09-02T07:54:08Z","Input":{"Bucket":"test-local-dev","Location":"oss-cn-hangzhou","Audio":[],"Container":[],"Object":"ec9e271583a4f5a799065d6690e56b8d.mp4"},"State":"TranscodeSuccess","FinishTime":"2019-09-02T07:54:12Z","MNSMessageResult":{"MessageId":"02912CE53ADA7FC6173E088FF6B1F41D"},"JobId":"c17ffde970f9402ea477ea75725f5af2","PipelineId":"8c11b2ed531049e4b3a14e0774c748bc","Percent":100,"Output":{"TransConfig":[],"MuxConfig":{"Gif":[],"Segment":[],"Webp":[]},"Encryption":[],"OutputFile":{"Bucket":"test-local-dev","Location":"oss-cn-hangzhou","Object":"2k-ec9e271583a4f5a799065d6690e56b8d.mp4"},"SubtitleConfig":[],"UserData":"2k","Clip":{"TimeSpan":[]},"TemplateId":"7ad95ada6dd5497b8517d8f845721f4d","Priority":"6","Properties":{"FileSize":"971144","Format":{"FormatName":"mov,mp4,m4a,3gp,3g2,mj2","Duration":"23.383000","FormatLongName":"QuickTime \/ MOV","NumStreams":"2","StartTime":"-0.046440","Bitrate":"332.256","NumPrograms":"0","Size":"971144"},"FileFormat":"mp4","Duration":"23","Height":"480","Width":"272","Fps":"30","Streams":{"SubtitleStreamList":{"SubtitleStream":[]},"AudioStreamList":{"AudioStream":[{"Lang":"und","SampleFmt":"fltp","CodecName":"aac","CodecTimeBase":"1\/44100","Timebase":"1\/44100","CodecTag":"0x6134706d","Channels":"2","ChannelLayout":"stereo","Index":"1","CodecTagString":"mp4a","Samplerate":"44100","Duration":"23.382494","CodecLongName":"AAC (Advanced Audio Coding)","StartTime":"-0.046440","Bitrate":"127.999"}]},"VideoStreamList":{"VideoStream":[{"Lang":"und","PixFmt":"yuv420p","NetworkCost":[],"Dar":"0:1","Profile":"Constrained Baseline","Height":"480","Sar":"0:1","CodecName":"h264","Timebase":"1\/15360","CodecTimeBase":"1\/60","CodecTag":"0x31637661","HasBFrames":"0","Index":"0","CodecTagString":"avc1","Duration":"23.366667","AvgFPS":"30.0","CodecLongName":"H.264 \/ AVC \/ MPEG-4 AVC \/ MPEG-4 part 10","Level":"21","StartTime":"0.000000","Width":"272","Fps":"30.0","Bitrate":"197.189"}]}},"Bitrate":"332"},"M3U8NonStandardSupport":{"TS":[]},"SuperReso":[],"Audio":{"Volume":[]},"Container":[],"Video":{"BitrateBnd":[]}}}]},"RequestId":"198ACE46-6ADA-42C8-9D92-97319E91732E"}}
*/

//提交转码作业
    $fileName = 'ec9e271583a4f5a799065d6690e56b8d.mp4';
    $outputs = json_encode([
        [
            'OutputObject' => '2k' . '-' .$fileName,
            'TemplateId' => config("mts.transcode.2k"),
            'UserData' => '2k',
        ],
        [
            'OutputObject' => '4k' . '-' .$fileName,
            'TemplateId' => config("mts.transcode.4k"),
            'UserData' => '4k',
        ],
    ]);

    $mts->submitJobs($fileName, $outputs);
    /**
    {"state":true,"data":{"RequestId":"5DC844E6-DC49-456D-B0E7-2A8C6B6C6D60","JobResultList":{"JobResult":[{"Job":{"CreationTime":"2019-09-02T09:14:18Z","Input":{"Bucket":"test-local-dev","Location":"oss-cn-hangzhou","Audio":[],"Container":[],"Object":"ec9e271583a4f5a799065d6690e56b8d.mp4"},"State":"Submitted","MNSMessageResult":[],"JobId":"d461043d77154a42a6fc288bf9e53986","PipelineId":"8c11b2ed531049e4b3a14e0774c748bc","Percent":0,"Output":{"TransConfig":[],"MuxConfig":{"Gif":[],"Segment":[],"Webp":[]},"Encryption":[],"OutputFile":{"Bucket":"test-local-dev","Location":"oss-cn-hangzhou","Object":"2k-ec9e271583a4f5a799065d6690e56b8d.mp4"},"SubtitleConfig":[],"UserData":"2k","Clip":{"TimeSpan":[]},"TemplateId":"7ad95ada6dd5497b8517d8f845721f4d","Priority":"6","Properties":{"Format":[],"Streams":[]},"M3U8NonStandardSupport":{"TS":[]},"SuperReso":[],"Audio":{"Volume":[]},"Container":[],"Video":{"BitrateBnd":[]},"DigiWaterMark":{"InputFile":[]}}},"Success":true},{"Job":{"CreationTime":"2019-09-02T09:14:18Z","Input":{"Bucket":"test-local-dev","Location":"oss-cn-hangzhou","Audio":[],"Container":[],"Object":"ec9e271583a4f5a799065d6690e56b8d.mp4"},"State":"Submitted","MNSMessageResult":[],"JobId":"db7f1752e7674bc3bc3cd212192558db","PipelineId":"8c11b2ed531049e4b3a14e0774c748bc","Percent":0,"Output":{"TransConfig":[],"MuxConfig":{"Gif":[],"Segment":[],"Webp":[]},"Encryption":[],"OutputFile":{"Bucket":"test-local-dev","Location":"oss-cn-hangzhou","Object":"4k-ec9e271583a4f5a799065d6690e56b8d.mp4"},"SubtitleConfig":[],"UserData":"4k","Clip":{"TimeSpan":[]},"TemplateId":"ecc46eb7d8944308b12b621d0efe1dd4","Priority":"6","Properties":{"Format":[],"Streams":[]},"M3U8NonStandardSupport":{"TS":[]},"SuperReso":[],"Audio":{"Volume":[]},"Container":[],"Video":{"BitrateBnd":[]},"DigiWaterMark":{"InputFile":[]}}},"Success":true}]}}}
    */
```



## Contributing

You can contribute in one of three ways:

1. File bug reports using the [issue tracker](https://github.com/cksaa/laravel-ali-mts/issues).
2. Answer questions or fix bugs on the [issue tracker](https://github.com/cksaa/laravel-ali-mts/issues).
3. Contribute new features or update the wiki.

_The code contribution process is not very formal. You just need to make sure that you follow the PSR-0, PSR-1, and PSR-2 coding guidelines. Any new code contributions must be accompanied by unit tests where applicable._

## License

MIT