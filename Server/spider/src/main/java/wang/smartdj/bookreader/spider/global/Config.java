package wang.smartdj.bookreader.spider.global; /**
 * Created by Arthur on 2016/12/12.
 */

import com.mchange.v2.c3p0.ComboPooledDataSource;
import org.slf4j.Logger;
import org.slf4j.LoggerFactory;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.context.annotation.Bean;
import org.springframework.context.annotation.ComponentScan;
import org.springframework.context.annotation.Configuration;
import org.springframework.context.annotation.PropertySource;
import org.springframework.core.env.Environment;
import org.springframework.dao.annotation.PersistenceExceptionTranslationPostProcessor;
import org.springframework.data.jpa.repository.config.EnableJpaRepositories;
import org.springframework.orm.jpa.JpaTransactionManager;
import org.springframework.orm.jpa.JpaVendorAdapter;
import org.springframework.orm.jpa.LocalContainerEntityManagerFactoryBean;
import org.springframework.orm.jpa.vendor.HibernateJpaVendorAdapter;
import org.springframework.transaction.annotation.EnableTransactionManagement;

import javax.persistence.EntityManagerFactory;
import javax.sql.DataSource;
import java.beans.PropertyVetoException;
import java.sql.SQLException;
import java.util.Properties;

@Configuration //当通过手动注册配置类的时候，这个可以不写，如果想通过应用程序上下文获得这个bean，这个必须写
@PropertySource("classpath:hibernate.properties")
@EnableJpaRepositories(basePackages = {"wang.smartdj.bookreader.spider"})
@EnableTransactionManagement
@ComponentScan("wang.smartdj.bookreader.spider")
public class Config {
    @Autowired
    private Environment env;

    private final Logger logger = LoggerFactory.getLogger(Config.class);

//    private static final String PROPERTY_NAME_DATABASE_DRIVER = Database.PROPERTY_NAME_DATABASE_DRIVER;
//    private static final String PROPERTY_NAME_DATABASE_URL = Database.PROPERTY_NAME_DATABASE_URL;
//    private static final String PROPERTY_NAME_DATABASE_USERNAME = Database.PROPERTY_NAME_DATABASE_USERNAME;
//    private static final String PROPERTY_NAME_DATABASE_PASSWORD = Database.PROPERTY_NAME_DATABASE_PASSWORD;
    private static final String PROPERTY_NAME_HIBERNATE_METADATA = "hibernate.temp.use_jdbc_metadata_defaults";
    private static final String PROPERTY_NAME_ENTITYMANAGER_PACKAGES_TO_SCAN = "wang.smartdj.bookreader.spider.entity";

    private static final String PROPERTY_NAME_DB_DRIVER_CLASS = "db.driver";
    private static final String PROPERTY_NAME_DB_PASSWORD = "db.password";
    private static final String PROPERTY_NAME_DB_URL = "db.url";
    private static final String PROPERTY_NAME_DB_USER = "db.username";
    private static final String PROPERTY_NAME_HIBERNATE_DIALECT = "hibernate.dialect";
    private static final String PROPERTY_NAME_HIBERNATE_FORMAT_SQL = "hibernate.format_sql";
    private static final String PROPERTY_NAME_HIBERNATE_HBM2DDL_AUTO = "hibernate.hbm2ddl.auto";
    private static final String PROPERTY_NAME_HIBERNATE_NAMING_STRATEGY = "hibernate.ejb.naming_strategy";
    private static final String PROPERTY_NAME_HIBERNATE_SHOW_SQL = "hibernate.show_sql";

    public Config() {
    }

    @Bean
    public LocalContainerEntityManagerFactoryBean entityManagerFactory()
    {
        LocalContainerEntityManagerFactoryBean entityManagerFactoryBean = new LocalContainerEntityManagerFactoryBean();
        entityManagerFactoryBean.setDataSource(dataSource());
        entityManagerFactoryBean.setJpaVendorAdapter(new HibernateJpaVendorAdapter());
        entityManagerFactoryBean.setPackagesToScan(PROPERTY_NAME_ENTITYMANAGER_PACKAGES_TO_SCAN);

        Properties jpaProperties = new Properties();

        //Configures the used database dialect. This allows Hibernate to create SQL
        //that is optimized for the used database.
        jpaProperties.put(PROPERTY_NAME_HIBERNATE_DIALECT, env.getRequiredProperty("hibernate.dialect"));

        //If the value of this property is true, Hibernate writes all SQL
        //statements to the console.
        jpaProperties.put(PROPERTY_NAME_HIBERNATE_SHOW_SQL, env.getRequiredProperty("hibernate.show_sql"));

        //If the value of this property is true, Hibernate will format the SQL
        //that is written to the console.
        jpaProperties.put(PROPERTY_NAME_HIBERNATE_FORMAT_SQL, env.getRequiredProperty("hibernate.format_sql"));

        jpaProperties.setProperty(PROPERTY_NAME_HIBERNATE_METADATA, env.getRequiredProperty("hibernate.temp.use_jdbc_metadata_defaults"));

//        jpaProperties.setProperty("org.hibernate.flushMode", env.getRequiredProperty("org.hibernate.flushMode"));
        //二级缓存
        jpaProperties.setProperty("hibernate.cache.use_second_level_cache", "true");
        jpaProperties.setProperty("hibernate.cache.use_query_cache", "true");
        jpaProperties.setProperty("hibernate.cache.region.factory_class", "org.hibernate.cache.ehcache.EhCacheRegionFactory");
        //jpaProperties.setProperty("javax.persistence.sharedCache.mode", "ENABLE_SELECTIVE");
        //jpaProperties.setProperty("hibernate.generate_statistics", "true");

        entityManagerFactoryBean.setJpaProperties(jpaProperties);

        return entityManagerFactoryBean;
    }

    @Bean//(destroyMethod = "close")
    public DataSource dataSource()
    {
        ComboPooledDataSource dataSource = new ComboPooledDataSource();
        try
        {
            dataSource.setDriverClass(env.getRequiredProperty(PROPERTY_NAME_DB_DRIVER_CLASS));
            dataSource.setJdbcUrl(env.getRequiredProperty(PROPERTY_NAME_DB_URL));
            dataSource.setUser(env.getRequiredProperty(PROPERTY_NAME_DB_USER));
            dataSource.setPassword(env.getRequiredProperty(PROPERTY_NAME_DB_PASSWORD));
            dataSource.setMinPoolSize(10);
            dataSource.setInitialPoolSize(20);
            dataSource.setMaxPoolSize(300);
        }
        catch (PropertyVetoException e)
        {
            e.printStackTrace();
        }

        return dataSource;
    }

    @Bean
    JpaTransactionManager transactionManager(EntityManagerFactory entityManagerFactory) {
        JpaTransactionManager transactionManager = new JpaTransactionManager();
        transactionManager.setEntityManagerFactory(entityManagerFactory);
        return transactionManager;
    }

    @Bean
    public PersistenceExceptionTranslationPostProcessor exceptionTranslation()
    {
        return new PersistenceExceptionTranslationPostProcessor();
    }
}
