package wang.smartdj.bookreader.spider.global; /**
 * Created by Arthur on 2016/12/12.
 */

import org.slf4j.Logger;
import org.slf4j.LoggerFactory;
import org.springframework.context.annotation.Bean;
import org.springframework.data.jpa.repository.config.EnableJpaRepositories;
import org.springframework.orm.jpa.LocalContainerEntityManagerFactoryBean;
import org.springframework.orm.jpa.vendor.HibernateJpaVendorAdapter;

import javax.persistence.EntityManagerFactory;
import java.sql.SQLException;

@EnableJpaRepositories("wang.smartdj.bookreader.spider")
public class Config {
    public final Logger logger = LoggerFactory.getLogger(Config.class);

    public Config() {
    }


}
