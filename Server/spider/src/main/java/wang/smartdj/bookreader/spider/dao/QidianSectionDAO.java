package wang.smartdj.bookreader.spider.dao;

import org.springframework.data.jpa.repository.JpaRepository;
import org.springframework.stereotype.Component;
import wang.smartdj.bookreader.spider.model.qidian.QidianSectionModel;

/**
 * Created by Arthur on 2016/12/16.
 */

@Component("QidianSectionDAO")
public interface QidianSectionDAO  extends JpaRepository<QidianSectionModel, Integer> {
    public long count();
}
