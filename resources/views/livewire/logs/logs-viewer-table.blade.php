<div>

    <table id="table-log" class="table table-hover table-bordered" data-ordering-index="{{ $standardFormat ? 2 : 0 }}">
        <thead>
        <tr class="blue-background text-white">
            @if ($standardFormat)
                <th>Level</th>
                <th>Time</th>
            @else
                <th>Line number</th>
            @endif
            <th>Content</th>
        </tr>
        </thead>
        <tbody>

        @foreach($logs as $key => $log)
            <tr data-display="stack{{{$key}}}">
                @if ($standardFormat)
                    <td class="nowrap text-{{{$log['level_class']}}}">
                        <span class="fa fa-{{{$log['level_img']}}}" aria-hidden="true"></span>&nbsp;&nbsp;{{$log['level']}}
                    </td>
                @endif
                <td class="date">{{\Carbon\Carbon::parse($log['date'])->format('H:i:s')}}</td>
                <td class="text">
                    @if ($log['stack'])
                        <button type="button"
                                class="float-right expand btn btn-outline-dark btn-sm mb-2 ml-2"
                                data-display="stack{{{$key}}}">
                            <span class="fa fa-search"></span>
                        </button>
                    @endif
                    {{{$log['text']}}}
                    @if (isset($log['in_file']))
                        <br/>{{{$log['in_file']}}}
                    @endif
                    @if ($log['stack'])
                        <div class="stack" id="stack{{{$key}}}"
                             style="display: none; white-space: pre-wrap;">{{{ trim($log['stack']) }}}
                        </div>
                    @endif
                </td>
            </tr>
        @endforeach

        </tbody>
    </table>
</div>
