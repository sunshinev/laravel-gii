@extends('gii_views::index')
<style>
    .ivu-table .demo-table-info-cell {
        background-color: #2db7f5;
        color: #fff;
    }

    .ivu-table .demo-table-warning-cell {
        background-color: #ff6600;
        color: #fff;
    }

    .ivu-table .demo-table-error-cell {
        background-color: #187;
        color: #fff;
    }
</style>
@section('content')
    <i-row>
        <i-col span="12">
            <i-form :label-width="200" method="post">
                <i-form-item label="Controller">
                    <i-input name="controller_class_name" value="{{request()->controller_class_name}}"
                             placeholder="ex: App\Http\Controllers\{module_name}\{controller_name}"
                             :autocomplete="true"></i-input>
                    {{--<i-auto-complete  value="{{request()->controller_class_name}}" name="controller_class_name" placeholder="click to choose or input custom"></i-auto-complete>--}}
                </i-form-item>
                <i-form-item label="Model name">
                    <i-input name="model_class_name" value="{{request()->model_class_name}}" :autocomplete="true"
                             placeholder="ex: App\Models\User\Mongo\Userinfo"></i-input>
                </i-form-item>
                <i-form-item>
                    <i-button type="primary" html-type="submit" name="preview">Preview</i-button>
                    @if(isset($files))
                        <i-button type="success" html-type="submit" name="generate" :value="1">Generate</i-button>
                        <input type="hidden" name="waitingfiles" v-model="waitingfiles">
                    @endif
                </i-form-item>
                {{csrf_field()}}
            </i-form>
            @if( isset($alert))
                <i-alert type="{{$alert['type']}}" show-icon>
                    {{$alert['type']}}
                    <span slot="desc">
                        {{$alert['message']}}
                    </span>
                </i-alert>
            @endif
        </i-col>
    </i-row>
    @if(isset($generate_info))
        @foreach($generate_info as $file)
            <i-alert type="{{$file['status']['type']}}" show-icon>
                {{$file['status']['type']}} : {{$file['virtual_path']}}:{{$file['status']['message']}}
            </i-alert>
        @endforeach
    @endif
    <i-row>
        <i-col span="20">
            <div style="text-align: right">
                <i-button @click="handleSelectAll(true)">Select all</i-button>
                <i-button @click="handleSelectAll(false)">Unselect all</i-button>
            </div>
            <br>
            <i-table :columns="table_col" :data="table_data" ref="selection"
                     @on-selection-change="table_selection_change">
                <template slot-scope="{ row, index }" slot="table_button">
                    <i-button type="info" size="small" @click="openModal(index)">Detail</i-button>
                    <template v-if="row.isdiff == 'y'">
                        <i-button type="warning" size="small" @click="openDiffModal(index)">diff</i-button>
                    </template>
                </template>
            </i-table>
        </i-col>
    </i-row>
    @foreach ($files as $key=>$file)
        <i-modal v-model="modal{{$key}}" title="{{$file['path']}}" width="80">
            <pre><code v-text="modalcontent{{$key}}"></code></pre>
        </i-modal>
        @if($file['diff_content'])
            <i-modal v-model="diffmodal{{$key}}" title="{{$file['path']}}" width="80">
                <div id="diffcode{{$key}}" v-html="diffmodalcontent{{$key}}"></div>
            </i-modal>
        @endif
    @endforeach
@endsection
@section('assets')
    <link rel="stylesheet"
          href="//cdn.jsdelivr.net/gh/highlightjs/cdn-release@9.16.2/build/styles/default.min.css">
    <script src="//cdn.jsdelivr.net/gh/highlightjs/cdn-release@9.16.2/build/highlight.min.js"></script>
    <script>hljs.initHighlightingOnLoad();</script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/diff2html/2.12.1/diff2html.min.js"
            integrity="sha256-cTH7epla2XtsxlSV0eHjVwQ8WLbvbs57MPtOh4K7DjM=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/diff2html/2.12.1/diff2html-ui.min.js"
            integrity="sha256-08hEwaeqghdW6TAaEo1EGHDC/mrLMaWl6gRlMqV+54I=" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/diff2html/2.12.1/diff2html.min.css"
          integrity="sha256-g/AR0iN639QFgl5CwIQoH9JOlWFuPJ2L9kRi+sKOmxA=" crossorigin="anonymous"/>
@endsection

@section('new_vue')
    <script>
        new Vue({
            data: {
                table_data: [
                        @foreach ($files as $key => $file)
                    {
                        path: "{{$file['virtual_path']}}",
                        isdiff: "{{$file['is_diff']}}",
                        cellClassName: {
                            @if($file['is_new_file'] == 'y')
                            path: 'demo-table-info-cell',
                            @elseif($file['is_diff'] == 'y')
                            path: 'demo-table-warning-cell'
                            @endif
                        }
                    },
                    @endforeach
                ],
                @foreach ($files as $key => $file)
                modal{{$key}}: false,
                modalcontent{{$key}} : decodeURIComponent(`{{$file['content']}}`),
                diffmodal{{$key}}: false,
                @endforeach
                table_col: [
                    {
                        type: 'index',
                        width: 60,
                        align: 'center'
                    },
                    {
                        title: 'path',
                        key: 'path',
                    },
                    {
                        title: 'operation',
                        slot: "table_button",
                        width: 200,
                    },
                    {
                        type: 'selection',
                        width: 60,
                        align: 'center'
                    },
                ],
                waitingfiles: []
            },
            computed: {
                @foreach($files as $key=>$file)
                        @if($file['diff_content'])
                diffmodalcontent{{$key}} () {
                    return Diff2Html.getPrettyHtml(
                        decodeURIComponent(`{!! $file['diff_content']  !!}`),
                        {inputFormat: 'diff', showFiles: true, matching: 'lines', outputFormat: 'line-by-line'}
                    );
                },
                @endif
                @endforeach
                getActiveName: function() {
                    return 'crud'
                }
            },
            methods: {
                openModal(index) {
                    this['modal' + index] = true
                },
                openDiffModal(index) {
                    this['diffmodal' + index] = true
                },
                handleSelectAll(status) {
                    this.$refs.selection.selectAll(status);
                },
                table_selection_change(rows) {
                    this.waitingfiles = []
                    for (var i in rows) {
                        this.waitingfiles.push(rows[i].path);
                    }
                }
            }
        }).$mount('#app');
    </script>
@endsection