package wang.smartdj.bookreader.spider.global; /**
 * Created by Arthur on 2016/12/12.
 */

import org.slf4j.Logger;
import org.slf4j.LoggerFactory;
import org.springframework.data.jpa.repository.config.EnableJpaRepositories;

@EnableJpaRepositories
public class Config {
    public static final Logger logger = LoggerFactory.getLogger(Config.class);

    public Config() {
    }


}
