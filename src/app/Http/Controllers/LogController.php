<?php

namespace Sheeraz\LogManager\app\Http\Controllers;

use Mail;
use App\Models\User;
use Sheeraz\LogManager\app\Classes\LogViewer;
use Illuminate\Routing\Controller;

class LogController extends Controller
{
    /**
     * Lists all log files.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $this->data['files'] = LogViewer::getFiles(true, true);
        $this->data['title'] = 'Log Manager';

        return view('logmanager::logs', $this->data);
    }

    /**
     * Previews a log file.
     *
     * @throws \Exception
     */
    public function preview($file_name)
    {
        LogViewer::setFile(decrypt($file_name));

        $logs = LogViewer::all();

        if (count($logs) <= 0) {
            abort(404, 'Log file doesn\'t exist');
        }

        $this->data['logs'] = $logs;
        $this->data['title'] = 'Preview'.' '.'Logs';
        $this->data['file_name'] = decrypt($file_name);

        return view('logmanager::log_item', $this->data);
    }

    /**
     * Downloads a log file.
     *
     * @param $file_name
     *
     * @throws \Exception
     *
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function download($file_name)
    {
        return response()->download(LogViewer::pathToLogFile(decrypt($file_name)));
    }

    /**
     * Mail user view.
     *
     * @throws \Exception
     */
    public function mail($file_name)
    {
        LogViewer::setFile(decrypt($file_name));

        $logs = LogViewer::all();
        $users = User::all();

        if (count($logs) <= 0) {
            abort(404, 'Log file doesn\'t exist');
        }

        $this->data['logs'] = $logs;
        $this->data['users'] = $users;
        $this->data['title'] = 'Email'.' '.'Logs';
        $this->data['file_name'] = decrypt($file_name);

        return view('logmanager::log_item_mail', $this->data);
    }

    /**
     * Mail log to user.
     *
     * @throws \Exception
     */
    public function mail_log_to_user($file_name,$user_id)
    {
        $file = LogViewer::pathToLogFile(decrypt($file_name));

        $user = User::findOrFail($user_id);

        $data["email"] = "sheeraz@test.com";
        $data["title"] = "Test";
        $data["body"] = "This is Demo";

        Mail::send('logmanager::log_mail_view', $data, function ($message) use ($user,$file) {
            $message->from('sheeraz@test.com', config('app.name'));
            $message->to($user->email, $user->name)->subject('Exception logs');
            $message->attach($file);
        });

        $this->data['files'] = LogViewer::getFiles(true, true);
        $this->data['title'] = 'Log Manager';

        return view('logmanager::logs', $this->data)->with('message', 'Mail has been sent');
    }

    /**
     * Deletes a log file.
     *
     * @param $file_name
     *
     * @throws \Exception
     *
     * @return string
     */
    public function delete($file_name)
    {
        if (config('backpack.logmanager.allow_delete') == false) {
            abort(403);
        }

        if (app('files')->delete(LogViewer::pathToLogFile(decrypt($file_name)))) {
            return 'success';
        }

        abort(404, trans('backpack::logmanager.log_file_doesnt_exist'));
    }
}
