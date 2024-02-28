#include <iostream>
#include <mysql_connection.h>
#include <driver.h>
#include <statement.h>
#include <resultset.h>

using namespace std;
using namespace sql;

int main() {
    try {
        // Create a connection
        sql::Driver *driver;
        sql::Connection *con;

        driver = get_driver_instance();
        con = driver->connect("tcp://127.0.0.1:3306", "username", "password"); // Change username and password accordingly
        con->setSchema("hamro_mart");

        // Execute a query
        sql::Statement *stmt;
        sql::ResultSet *res;

        stmt = con->createStatement();
        res = stmt->executeQuery("SELECT * FROM products");

        // Display the results
        cout << "ProductID | ProductName | Price | Quantity" << endl;
        while (res->next()) {
            cout << res->getInt("ProductID") << " | "
                 << res->getString("ProductName") << " | "
                 << res->getDouble("Price") << " | "
                 << res->getInt("Quantity") << endl;
        }

        delete res;
        delete stmt;
        delete con;
    } catch (sql::SQLException &e) {
        cout << "# ERR: SQLException in " << __FILE__;
        cout << "(" << __FUNCTION__ << ") on line " << __LINE__ << endl;
        cout << "# ERR: " << e.what();
        cout << " (MySQL error code: " << e.getErrorCode();
        cout << ", SQLState: " << e.getSQLState() << " )" << endl;
    }

    return 0;
}
