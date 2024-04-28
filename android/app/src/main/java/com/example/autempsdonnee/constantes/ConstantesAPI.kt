import org.json.JSONObject
import java.io.File

class ConstantesAPI {

    object AppConstants {
        val API_BASE_URL = "http://192.168.1.22:8081/index.php"
    }

   /* object AppConstants {
        private val configFile = File("../ip.json")
        private val config = JSONObject(configFile.readText())
        val API_BASE_URL = "http://"+config.getString("ipAddress")+":8081/index.php"
    }*/

}