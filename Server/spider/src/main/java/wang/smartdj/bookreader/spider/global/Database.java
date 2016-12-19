package wang.smartdj.bookreader.spider.global;

/**
 * Created by Arthur on 2016/12/19.
 */
public class Database {
    public static final String PROPERTY_NAME_DATABASE_DRIVER 	= "net.sf.log4jdbc.DriverSpy";
    public static final String PROPERTY_NAME_DATABASE_URL 		= "jdbc:log4jdbc:mysql://127.0.0.1:3306/duwa?zeroDateTimeBehavior=convertToNull&useUnicode=true&characterEncoding=UTF-8&autoReconnect=true";
    public static final String PROPERTY_NAME_DATABASE_USERNAME = "root";
    public static final String PROPERTY_NAME_DATABASE_PASSWORD = "";

    public static final String HINT_CACHEABLE					= "org.hibernate.cacheable";
}
