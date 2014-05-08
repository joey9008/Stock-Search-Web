import java.net.MalformedURLException;
import java.net.URL;
import java.net.URLConnection;
import org.json.JSONException;
import org.json.JSONObject;
import org.json.XML;

import java.io.*;
import javax.servlet.*;
import javax.servlet.http.*;


public class HelloWorldExample extends HttpServlet {


    public void doGet(HttpServletRequest request,
                      HttpServletResponse response)
        throws IOException, ServletException
    {
        response.setContentType("application/json;charset=utf-8");
        response.setHeader("Access-Control-Allow-Origin", "*"); 
        String symbol= request.getParameter("symbol");
        if(symbol == null)
            return;
        else
        {
            try{
                URL url = new URL("http://csci571-env.elasticbeanstalk.com/?symbol=" + symbol);
                URLConnection URLConnectionurlConnection = url.openConnection();
                URLConnectionurlConnection.setAllowUserInteraction(false);
                BufferedReader bufferedReader = new BufferedReader(new InputStreamReader(url.openStream()));
                String line;
                String xmlConent = "";
                while((line = bufferedReader.readLine())!= null)
                {
                    xmlConent += line;
                }
                PrintWriter out = response.getWriter();
                JSONObject jsonObject = XML.toJSONObject(xmlConent);
                out.print(jsonObject);
            }catch(MalformedURLException e){
                System.out.println("MalformedURLException: " + e);
            } catch (JSONException e) {
                e.printStackTrace();
            }
        } 
    }
}



