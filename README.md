## Transaction engine system

### Main takeaway from the task

* Use SOLID and GRASP principles
* Business logic is the main focus
* No MVC allowed
* UI is not needed
* Frameworks are not allowed
* RDBMS usage is not allowed
* Third party libraries could be used

### Functionality

* This project is written as console application
  *  Entry point is the <code>script/run.php</code>
  *  Each handler has it's own set of arguments described  in lower sections


### Checks

After installing project through <code>composer install</code>, run these commands to ensure code is checked:

* <code>vendor/bin/phpstan analyze scripts/</code>
* <code>vendor/bin/phpstan analyze src/</code>
* <code>vendor/bin/phpcs scripts/</code>
* <code>vendor/bin/phpcs src/</code>

### Storage

Transactions data is stored in the  <code>data/log.csv</code> mimicing the transaction log on real systems.

https://github.com/thephpleague/csv is used to manipulate the data.

### Actions

#### Make operation
* <code>scripts/run.php makeOperation deposit {accountId} 100</code>
  * <code>accountId</code> is alphanumeric string
  * <code>deposit</code> operation creates new account


* <code>scripts/run.php makeOperation withdrawal {accountId} 100</code>
* <code>scripts/run.php makeOperation transfer {accountId} 100 {transferAccountId}</code>


#### Get all accounts
* <code>scripts/run.php getAccounts</code>


#### Get account with balance
* <code>scripts/run.php getAccount {accountId}</code>

#### Get account transactions with sorting
* <code>scripts/run.php getTransactions {accountId} {sortField} {sortOrder}</code>
  * available fields for sort <code>dueDate, operation, comment, amount</code>
  * available sortOrder <code>ASC, DESC</code>

---

### Design patterns and OOP principles

Several design patterns and principles was considered and implemented.

#### SOLID and GRASP Principles

Each action is separated into distinct class, that implements <code>HandlerInterface</code>

<code>HandlerInterface</code> responsibility is to take arguments and call business logic classes specific to concrete action.

Each account operation is separated into distinct class, that extends abstract <code>OperationBuilder</code>.

<code>OperationBuilder</code> responsibility is to build <code>Operation</code> model class, that defines single operation

Operation types: **deposit**, **withdrawal** and **transfer**

---
High-level classes instantiated in the <code>run.php</code> script as **Container** pattern is not used, being overhead for this task.

Other classes instantiated through builders and factories, encapsulating class-creation.

Storage functionality is encapsulated in two classes:

<code>Storage</code> - being the log-level module

<code>TransactionRepository</code> - being the high-level module

**Dependency Inversion**, **Liskov Principle** and **Polymorphism** implemented at best in the <code>MakeOperatfionHandler</code> as **high-level Handler** depends on abstract **Operation**, not concrete implementation.

Each <code>HandlerInterface</code> implementor and each <code>OperationBuilder</code> extender consist only of logic that is needed for it's specific operation, complying with **Low Coupling and High Cohesion** principle. 

<code>Storage</code> class can easily be rewritten or substituted with any storage logic, complying with **Low Coupling** principle

---
#### Design patterns

<code>Simple Factory</code> is used creating <code>Account</code>, <code>Transaction</code> and <code>OperationBuilder</code> as basic approach to encapsulate class creation.

<code>Strategy</code> is used to implement and choose the right <code>HandlerInterface</code>

<code>Builder</code> was chosen to implement <code>Operation</code> creation as it gives more flexibility as opposed to <code>Factor</code> pattern.

---

### Tests

Because PHPUnit and Codeigniter are considered frameworks, I decided not to include those packages.






