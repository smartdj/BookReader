package wang.smartdj.bookreader.spider.duplicateremover;

import org.apache.http.NameValuePair;
import org.apache.http.message.BasicNameValuePair;
import us.codecraft.webmagic.Request;
import us.codecraft.webmagic.Task;
import us.codecraft.webmagic.scheduler.component.DuplicateRemover;

import java.util.Collections;
import java.util.HashSet;
import java.util.Set;
import java.util.concurrent.ConcurrentHashMap;

/**
 * Created by Arthur on 2016/12/20.
 */
public class PostDuplicateRemover implements DuplicateRemover{
    private HashSet<String> urls = new HashSet<String>();

    @Override
    public boolean isDuplicate(Request request, Task task) {
        return !urls.add(getUrlAndParam(request));
    }

    @Override
    public void resetDuplicateCheck(Task task) {
        urls.clear();
    }

    @Override
    public int getTotalRequestsCount(Task task) {
        return urls.size();
    }

    //把URL和POST的参数组合起来避免使用默认的去重，导致POST请求无法添加多条
    protected String getUrlAndParam(Request request) {
        String str = new String();
        NameValuePair[] nameValuePairs = (NameValuePair[]) request.getExtra("nameValuePair");
        for (NameValuePair pair:nameValuePairs) {
            str = pair.getName() + ":" + pair.getValue() + "&";
        }
        return str = request.getUrl() + "?" + str;
    }
}
