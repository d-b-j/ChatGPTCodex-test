import subprocess
import sys
import pyfiglet
import time
from datetime import datetime
from colorama import init, Fore, Back, Style
# from colorist import bg_green, bg_yellow, bg_red


from auth import login
from menu_data import menu
from billing import Billing
from order_management import OrderManager
from receipt import Receipt
from search_sort import SearchSort
from menu_manager import MenuManager

init(autoreset=True)  # Automatically reset after each print

def splash_screen():
    """
    Prints a splash screen to the console, including a figlet-formatted
    banner, a welcome message, and a separator line.
    """
    ascii_banner = pyfiglet.figlet_format("Central Perk Cafe")
    print(Fore.GREEN + Style.BRIGHT + ascii_banner)
    print(Fore.YELLOW + "=" * 60)
    print(Fore.CYAN + "Welcome` to the Smart Cafe Management System")
    print(Fore.YELLOW + "=" * 60)
    print(Style.RESET_ALL)

def loading_animation():
    """
    Print a loading animation to the console, consisting of five dots
    with a 0.5 second delay between each dot. After the animation is
    complete, print "System Ready!" to the console.
    """
    print("Starting System", end="")
    for _ in range(5):
        sys.stdout.write(".")
        sys.stdout.flush()
        time.sleep(0.5)
    print("\nSystem Ready!\n")


def admin_menu():
    """
    Display the general admin menu.
    """
    manager = MenuManager(menu)

    while True:
        print(
        Fore.CYAN + Style.BRIGHT + 
        "===== Admin Menu =====" + 
        Style.RESET_ALL )
        print(
        Fore.CYAN + Style.BRIGHT + 
        "=" * 50 )
        print(
        Fore.CYAN + Style.BRIGHT +
        "1. Take Order\n" +
        "2. Show Restaurant Menu\n" +
        "3. Search Item\n" +
        "4. Add Menu Item\n" + 
        "0. Exit\n" + 
        Style.RESET_ALL )

        choice = input(
                Back.BLUE +
                "Select an option: " +
                Style.RESET_ALL
                ).strip()

        if choice == "1":
            order_manager = OrderManager(menu, display_func=restaurant_menu)
            order = order_manager.take_order()
            billing = Billing(menu)
            subtotal, discount, final_total = billing.calculate_bill(order)
            show_order_summary(order, menu, subtotal, discount, final_total)
            order_complete_options_menu(order, menu, subtotal, discount, final_total)

        elif choice == "2":
            restaurant_menu(menu)
            restaurant_options_menu(manager)

        elif choice == "3":
            search_item_menu(menu)
            # search_options_menu()

        elif choice == "4":
            code = input("Enter new item code: ").strip()
            name = input("Enter item name: ").strip()
            try:
                price = int(input("Enter item price (LKR): ").strip())
            except ValueError:
                print("Invalid price. Must be a number.")
                continue
            availability_input = input("Is the item available? (yes/no): ").strip().lower()
            availability = availability_input in ["yes", "y", "true"]
            manager.add_item(code, name, price, availability)
            add_item_options_menu(manager)

        elif choice == "0":
            print(Fore.MAGENTA + "\nExiting system. Goodbye!" + Style.RESET_ALL)
            sys.exit(0)

        else:
            print(Fore.RED + "Invalid choice." + Style.RESET_ALL)


# --- Restaurant Menu Display ---
def restaurant_menu(menu):
    """ 
    Display menu items using MenuManager. 
    """ 
    manager = MenuManager(menu) 
    manager.show_menu()

def order_complete_options_menu(order, menu, subtotal, discount, final_total):
    """
    Display options menu after order summary and handle actions.

    Provides options to show receipt, add more items, or cancel.
    """
    order_manager = OrderManager(menu, display_func=restaurant_menu)
    billing = Billing(menu)
    manager = MenuManager(menu)

    while True:
        print("===== Options =====")
        print("1. Show Receipt")
        print("2. Add More Items")
        print("3. Menu")
        print("0. Cancel")

        choice = input(
            Back.BLUE + "Option: " + Style.RESET_ALL
        ).strip()

        if choice == "1":
            receipt = Receipt()
            receipt.generate_receipt(order, menu, subtotal, discount, final_total)

        elif choice == "2":
            print(Fore.GREEN + "\nAdding more items to current order...\n" + Style.RESET_ALL)
            order = order_manager.take_order(existing_order=order)
            subtotal, discount, final_total = billing.calculate_bill(order)
            show_order_summary(order, menu, subtotal, discount, final_total)

        elif choice == "3":
            # Unified menu display
            manager.show_menu()

        elif choice == "0":
            start_system()
            return

        else:
            print(Fore.RED + "Invalid choice." + Style.RESET_ALL)

def search_item_menu(menu):
    """
    Prompt user to search for an item by code or name.
    """
    search_sort = SearchSort(menu)
    while True:
        print( 
        Fore.LIGHTBLACK_EX + 
        "\n\nUse 'find <keyword>' to search item or 'exit' to return to main menu." + 
        Style.RESET_ALL )

        query = input ( 
                Back.BLUE + 
                "Enter search term: " + 
                Style.RESET_ALL ).strip()    

        if query.lower().startswith("exit"):
            admin_menu()

        if query.lower().startswith("find"):
            keyword = query[4:].strip()
            result = search_sort.search_item(keyword)  
            if result:
                    print(
                    Fore.GREEN +
                    f"\n\nFound: {result['name']} - LKR {result['price']} - {result['availability']} " +
                    Style.RESET_ALL )                  
            else:
                print(
                Fore.RED +
                "\n\nItem not found." +
                Style.RESET_ALL )                        
        else:
            print(
            Fore.RED +
            "\n\nInvalid search command. Use 'find <keyword>'." +
            Style.RESET_ALL )


            
def restaurant_options_menu(manager):
    """
    Options after showing restaurant menu.
    """
    search_sort = SearchSort(manager.menu)
    while True:
        print(Fore.LIGHTBLACK_EX)
        print("\n===== Restaurant Menu Options =====")
        print("1. Sort Menu by Name")
        print("2. Sort Menu by Price")
        print("0. Return")
        print(Style.RESET_ALL)

        choice = input("Option: ").strip()
        if choice == "1":
            sorted_menu = search_sort.sort_menu(by="name")
            print(Fore.YELLOW + "\n===== Menu Sorted by Name =====")
            for code, name, price in sorted_menu:
                print(f"{code:<5} {name:<15} - LKR {price}")
            print(Fore.YELLOW + "===================================" + Style.RESET_ALL)    

        if choice == "2":
            sorted_menu = search_sort.sort_menu(by="price")
            print(Fore.YELLOW + "\n===== Menu Sorted by Price =====")
            for code, name, price in sorted_menu:
                print(f"{code:<5} {name:<15} - LKR {price}")
            print(Fore.YELLOW + "===================================" + Style.RESET_ALL)

        elif choice == "0":
            # start_system()
            return
        elif choice == "000":
            print(Fore.MAGENTA + "\nExiting system. Goodbye!" + Style.RESET_ALL)
            sys.exit(0)            
        else:
            pass
            # print(Fore.RED + "Invalid choice." + Style.RESET_ALL)

def search_options_menu():
    """
    Submenu for Search Item.
    """
    while True:
        print(Fore.LIGHTBLACK_EX)
        print("\n===== Search Options =====")
        print("1. Search Again")
        print("0. Return")
        print(Style.RESET_ALL)    

        choice = input("Option: ").strip()
        if choice == "1":
            search_item_menu(menu)
        elif choice == "0":
            start_system()
            return
        elif choice == "000":
            print(Fore.MAGENTA + "\nExiting system. Goodbye!" + Style.RESET_ALL)
            sys.exit(0)              
        else:
            print(Fore.RED + "Invalid choice." + Style.RESET_ALL)


def add_item_options_menu(manager):
    """
    Options after adding a menu item.
    """
    while True:
        print(Fore.LIGHTBLACK_EX)
        print("\n===== Add Item Options =====")
        print("1. Add Another Item")
        print("0. Return")
        print(Style.RESET_ALL)      

        choice = input("Option: ").strip()
        if choice == "1":
            code = input("Enter new item code: ").strip()
            name = input("Enter item name: ").strip()
            try:
                price = int(input("Enter item price (LKR): ").strip())
            except ValueError:
                print("Invalid price. Must be a number.")
                continue
            availability_input = input("Is the item available? (yes/no): ").strip().lower()
            availability = availability_input in ["yes", "y", "true"]
            manager.add_item(code, name, price, availability)
        elif choice == "0":
            start_system()
            return
        elif choice == "000":
            print(Fore.MAGENTA + "\nExiting system. Goodbye!" + Style.RESET_ALL)
            sys.exit(0)              
        else:
            print(Fore.RED + "Invalid choice." + Style.RESET_ALL)


def show_order_summary(order, menu, subtotal, discount, final_total):
    """Display a colorful summary of the order with item names and totals."""
    print(Fore.MAGENTA + Style.BRIGHT + "\n========== ORDER SUMMARY ==========" + Style.RESET_ALL)
    
    for code, qty in order.items():
        item_name = menu[code]["name"]
        unit_price = menu[code]["price"]
        total = unit_price * qty

        # Item line: code in yellow, name in cyan, qty in green, total in bright white
        print(
            Fore.YELLOW + f"{code}" + Style.RESET_ALL +
            " - " +
            Fore.CYAN + f"{item_name}" + Style.RESET_ALL +
            Fore.GREEN + f" (x{qty}) " + Style.RESET_ALL +
            "= " +
            Fore.WHITE + Style.BRIGHT + f"LKR {total}" + Style.RESET_ALL
        )

    print(Fore.BLUE + Style.BRIGHT + f"\nSubtotal: LKR {subtotal}" + Style.RESET_ALL)

    if discount > 0:
        print(Fore.RED + f"Discount Applied: -LKR {int(discount)}" + Style.RESET_ALL)

    print(Fore.GREEN + Style.BRIGHT + f"Final Total: LKR {int(final_total)}" + Style.RESET_ALL)
    print(Fore.MAGENTA + Style.BRIGHT + "===================================" + Style.RESET_ALL)


def start_system():
    """
    Run the startup sequence and show Admin Menu.
    """

    if login():  # Only proceed if login successful
        splash_screen()
        loading_animation()
        admin_menu()

if __name__ == "__main__":
    start_system()

        