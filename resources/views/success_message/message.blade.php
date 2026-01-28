@if (session('message'))
<div class="row massage">
  <div class="col-md-12 col-sm-12 col-xs-12">
    <div class="checkbox checkbox-success checkbox-circle mb-2 alert alert-success alert-dismissible fade show">

      @if (session('message') == 'Successfully Submitted')
      <label for="checkbox-10 colo_success" style="margin-left: 20px;font-weight: 600;"> {{ trans('message.Successfully Submitted') }} </label>
      @elseif(session('message') == 'Successfully Updated')
      <label for="checkbox-10 colo_success" style="margin-left: 20px;font-weight: 600;"> {{ trans('message.Successfully Updated') }} </label>
      @elseif(session('message') == 'Profile Setting Updated Successfully')
      <label for="checkbox-10 colo_success" style="margin-left: 20px;font-weight: 600;"> {{ trans('message.Profile Setting Updated Successfully') }} </label>
      @elseif(session('message') == 'Successfully Deleted')
      <label for="checkbox-10 colo_success" style="margin-left: 20px;font-weight: 600;"> {{ trans('message.Successfully Deleted') }} </label>
      @elseif(session('message') == 'Data not available')
      <label for="checkbox-10 colo_success" style="margin-left: 20px;font-weight: 600;"> {{ trans('message.Data not available') }}</label>
      @elseif(session('message') == 'Date is already inserted')
      <label for="checkbox-10 colo_success" style="margin-left: 20px;font-weight: 600;"> {{ trans('message.Date is already inserted') }}</label>
      @elseif(session('message') == 'Please select time which is greater than start time')
      <label for="checkbox-10 colo_success" style="margin-left: 20px;font-weight: 600;"> {{ trans('message.Please select time which is greater than start time') }}</label>
      @elseif(session('message') == 'This color is used with a vehicle record. So you can not delete it.')
      <label for="checkbox-10 colo_success" style="margin-left: 20px;font-weight: 600;"> {{ trans('message.This color is used with a vehicle record. So you can not delete it.') }}</label>
      @elseif(session('message') == 'Successfully Sent')
      <label for="checkbox-10 colo_success" style="margin-left: 20px;font-weight: 600;"> {{ trans('message.Successfully Sent') }}</label>
      @elseif(session('message') == 'Error! Something went wrong.')
      <label for="checkbox-10 colo_success" style="margin-left: 20px;font-weight: 600;"> {{ trans('message.Error! Something went wrong.') }}</label>
      @elseif(session('message') == 'Duplicate Data')
      <label for="checkbox-10 colo_success" style="margin-left: 20px;font-weight: 600;"> {{ trans('message.Duplicate Data') }}</label>
      @elseif(session('message') == 'Supplier Added Successfully')
      <label for="checkbox-10 colo_success" style="margin-left: 20px;font-weight: 600;"> {{ trans('message.Supplier Added Successfully') }}</label>
      @elseif(session('message') == 'Supplier Updated Successfully')
      <label for="checkbox-10 colo_success" style="margin-left: 20px;font-weight: 600;"> {{ trans('message.Supplier Updated Successfully') }}</label>
      @elseif(session('message') == 'Supplier Deleted Successfully')
      <label for="checkbox-10 colo_success" style="margin-left: 20px;font-weight: 600;"> {{ trans('message.Supplier Deleted Successfully') }}</label>

      @elseif(session('message') == 'Product Added Successfully')
      <label for="checkbox-10 colo_success" style="margin-left: 20px;font-weight: 600;"> {{ trans('message.Product Added Successfully') }}</label>
      @elseif(session('message') == 'Product Updated Successfully')
      <label for="checkbox-10 colo_success" style="margin-left: 20px;font-weight: 600;"> {{ trans('message.Product Updated Successfully') }}</label>
      @elseif(session('message') == 'Product Deleted Successfully')
      <label for="checkbox-10 colo_success" style="margin-left: 20px;font-weight: 600;"> {{ trans('message.Product Deleted Successfully') }}</label>

      @elseif(session('message') == 'Purchase Added Successfully')
      <label for="checkbox-10 colo_success" style="margin-left: 20px;font-weight: 600;"> {{ trans('message.Purchase Added Successfully') }}</label>
      @elseif(session('message') == 'Purchase Updated Successfully')
      <label for="checkbox-10 colo_success" style="margin-left: 20px;font-weight: 600;"> {{ trans('message.Purchase Updated Successfully') }}</label>
      @elseif(session('message') == 'Purchase Deleted Successfully')
      <label for="checkbox-10 colo_success" style="margin-left: 20px;font-weight: 600;"> {{ trans('message.Purchase Deleted Successfully') }}</label>

      @elseif(session('message') == 'Customer Added Successfully')
      <label for="checkbox-10 colo_success" style="margin-left: 20px;font-weight: 600;"> {{ trans('message.Customer Added Successfully') }}</label>
      @elseif(session('message') == 'Customer Updated Successfully')
      <label for="checkbox-10 colo_success" style="margin-left: 20px;font-weight: 600;"> {{ trans('message.Customer Updated Successfully') }}</label>
      @elseif(session('message') == 'Customer Deleted Successfully')
      <label for="checkbox-10 colo_success" style="margin-left: 20px;font-weight: 600;"> {{ trans('message.Customer Deleted Successfully') }}</label>

      @elseif(session('message') == 'Employee Added Successfully')
      <label for="checkbox-10 colo_success" style="margin-left: 20px;font-weight: 600;"> {{ trans('message.Employee Added Successfully') }}</label>
      @elseif(session('message') == 'Employee Updated Successfully')
      <label for="checkbox-10 colo_success" style="margin-left: 20px;font-weight: 600;"> {{ trans('message.Employee Updated Successfully') }}</label>
      @elseif(session('message') == 'Employee Deleted Successfully')
      <label for="checkbox-10 colo_success" style="margin-left: 20px;font-weight: 600;"> {{ trans('message.Employee Deleted Successfully') }}</label>

      @elseif(session('message') == 'Supportstaff Added Successfully')
      <label for="checkbox-10 colo_success" style="margin-left: 20px;font-weight: 600;"> {{ trans('message.Supportstaff Added Successfully') }}</label>
      @elseif(session('message') == 'Supportstaff Updated Successfully')
      <label for="checkbox-10 colo_success" style="margin-left: 20px;font-weight: 600;"> {{ trans('message.Supportstaff Updated Successfully') }}</label>
      @elseif(session('message') == 'Supportstaff Deleted Successfully')
      <label for="checkbox-10 colo_success" style="margin-left: 20px;font-weight: 600;"> {{ trans('message.Supportstaff Deleted Successfully') }}</label>

      @elseif(session('message') == 'Accountant Added Successfully')
      <label for="checkbox-10 colo_success" style="margin-left: 20px;font-weight: 600;"> {{ trans('message.Accountant Added Successfully') }}</label>
      @elseif(session('message') == 'Accountant Updated Successfully')
      <label for="checkbox-10 colo_success" style="margin-left: 20px;font-weight: 600;"> {{ trans('message.Accountant Updated Successfully') }}</label>
      @elseif(session('message') == 'Accountant Deleted Successfully')
      <label for="checkbox-10 colo_success" style="margin-left: 20px;font-weight: 600;"> {{ trans('message.Accountant Deleted Successfully') }}</label>

      @elseif(session('message') == 'Branch Admin Added Successfully')
      <label for="checkbox-10 colo_success" style="margin-left: 20px;font-weight: 600;"> {{ trans('message.Branch Admin Added Successfully') }}</label>
      @elseif(session('message') == 'Branch Admin Updated Successfully')
      <label for="checkbox-10 colo_success" style="margin-left: 20px;font-weight: 600;"> {{ trans('message.Branch Admin Updated Successfully') }}</label>
      @elseif(session('message') == 'Branch Admin Deleted Successfully')
      <label for="checkbox-10 colo_success" style="margin-left: 20px;font-weight: 600;"> {{ trans('message.Branch Admin Deleted Successfully') }}</label>

      @elseif(session('message') == 'Vehicle Added Successfully')
      <label for="checkbox-10 colo_success" style="margin-left: 20px;font-weight: 600;"> {{ trans('message.Vehicle Added Successfully') }}</label>
      @elseif(session('message') == 'Vehicle Updated Successfully')
      <label for="checkbox-10 colo_success" style="margin-left: 20px;font-weight: 600;"> {{ trans('message.Vehicle Updated Successfully') }}</label>
      @elseif(session('message') == 'Vehicle Deleted Successfully')
      <label for="checkbox-10 colo_success" style="margin-left: 20px;font-weight: 600;"> {{ trans('message.Vehicle Deleted Successfully') }}</label>

      @elseif(session('message') == 'Vehicle Type Added Successfully')
      <label for="checkbox-10 colo_success" style="margin-left: 20px;font-weight: 600;"> {{ trans('message.Vehicle Type Added Successfully') }}</label>
      @elseif(session('message') == 'Vehicle Type Updated Successfully')
      <label for="checkbox-10 colo_success" style="margin-left: 20px;font-weight: 600;"> {{ trans('message.Vehicle Type Updated Successfully') }}</label>
      @elseif(session('message') == 'Vehicle Type Deleted Successfully')
      <label for="checkbox-10 colo_success" style="margin-left: 20px;font-weight: 600;"> {{ trans('message.Vehicle Type Deleted Successfully') }}</label>

      @elseif(session('message') == 'Vehicle Brand Added Successfully')
      <label for="checkbox-10 colo_success" style="margin-left: 20px;font-weight: 600;"> {{ trans('message.Vehicle Brand Added Successfully') }}</label>
      @elseif(session('message') == 'Vehicle Brand Updated Successfully')
      <label for="checkbox-10 colo_success" style="margin-left: 20px;font-weight: 600;"> {{ trans('message.Vehicle Brand Updated Successfully') }}</label>
      @elseif(session('message') == 'Vehicle Brand Deleted Successfully')
      <label for="checkbox-10 colo_success" style="margin-left: 20px;font-weight: 600;"> {{ trans('message.Vehicle Brand Deleted Successfully') }}</label>

      @elseif(session('message') == 'Color Added Successfully')
      <label for="checkbox-10 colo_success" style="margin-left: 20px;font-weight: 600;"> {{ trans('message.Color Added Successfully') }}</label>
      @elseif(session('message') == 'Color Updated Successfully')
      <label for="checkbox-10 colo_success" style="margin-left: 20px;font-weight: 600;"> {{ trans('message.Color Updated Successfully') }}</label>
      @elseif(session('message') == 'Color Deleted Successfully')
      <label for="checkbox-10 colo_success" style="margin-left: 20px;font-weight: 600;"> {{ trans('message.Color Deleted Successfully') }}</label>

      @elseif(session('message') == 'Service Added Successfully')
      <label for="checkbox-10 colo_success" style="margin-left: 20px;font-weight: 600;"> {{ trans('message.Service Added Successfully') }}</label>
      @elseif(session('message') == 'Service Updated Successfully')
      <label for="checkbox-10 colo_success" style="margin-left: 20px;font-weight: 600;"> {{ trans('message.Service Updated Successfully') }}</label>
      @elseif(session('message') == 'Service Deleted Successfully')
      <label for="checkbox-10 colo_success" style="margin-left: 20px;font-weight: 600;"> {{ trans('message.Service Deleted Successfully') }}</label>
      @elseif(session('message') == 'Service Booked Successfully')
      <label for="checkbox-10 colo_success" style="margin-left: 20px;font-weight: 600;"> {{ trans('message.Service Deleted Successfully') }}</label>

      @elseif(session('message') == 'Quotation Created Successfully')
      <label for="checkbox-10 colo_success" style="margin-left: 20px;font-weight: 600;"> {{ trans('message.Quotation Created Successfully') }}</label>
      @elseif(session('message') == 'Quotation Updated Successfully')
      <label for="checkbox-10 colo_success" style="margin-left: 20px;font-weight: 600;"> {{ trans('message.Quotation Updated Successfully') }}</label>
      @elseif(session('message') == 'Quotation Deleted Successfully')
      <label for="checkbox-10 colo_success" style="margin-left: 20px;font-weight: 600;"> {{ trans('message.Quotation Deleted Successfully') }}</label>

      @elseif(session('message') == 'Invoice Created Successfully')
      <label for="checkbox-10 colo_success" style="margin-left: 20px;font-weight: 600;"> {{ trans('message.Invoice Created Successfully') }}</label>
      @elseif(session('message') == 'Invoice Updated Successfully')
      <label for="checkbox-10 colo_success" style="margin-left: 20px;font-weight: 600;"> {{ trans('message.Invoice Updated Successfully') }}</label>
      @elseif(session('message') == 'Invoice Deleted Successfully')
      <label for="checkbox-10 colo_success" style="margin-left: 20px;font-weight: 600;"> {{ trans('message.Invoice Deleted Successfully') }}</label>

      @elseif(session('message') == 'Gatepass Created Successfully')
      <label for="checkbox-10 colo_success" style="margin-left: 20px;font-weight: 600;"> {{ trans('message.Gatepass Created Successfully') }}</label>
      @elseif(session('message') == 'Gatepass Updated Successfully')
      <label for="checkbox-10 colo_success" style="margin-left: 20px;font-weight: 600;"> {{ trans('message.Gatepass Updated Successfully') }}</label>
      @elseif(session('message') == 'Gatepass Deleted Successfully')
      <label for="checkbox-10 colo_success" style="margin-left: 20px;font-weight: 600;"> {{ trans('message.Gatepass Deleted Successfully') }}</label>

      @elseif(session('message') == 'Tax Added Successfully')
      <label for="checkbox-10 colo_success" style="margin-left: 20px;font-weight: 600;"> {{ trans('message.Tax Added Successfully') }}</label>
      @elseif(session('message') == 'Tax Updated Successfully')
      <label for="checkbox-10 colo_success" style="margin-left: 20px;font-weight: 600;"> {{ trans('message.Tax Updated Successfully') }}</label>
      @elseif(session('message') == 'Tax Deleted Successfully')
      <label for="checkbox-10 colo_success" style="margin-left: 20px;font-weight: 600;"> {{ trans('message.Tax Deleted Successfully') }}</label>

      @elseif(session('message') == 'Payment Method Added Successfully')
      <label for="checkbox-10 colo_success" style="margin-left: 20px;font-weight: 600;"> {{ trans('message.Payment Method Added Successfully') }}</label>
      @elseif(session('message') == 'Payment Method Updated Successfully')
      <label for="checkbox-10 colo_success" style="margin-left: 20px;font-weight: 600;"> {{ trans('message.Payment Method Updated Successfully') }}</label>
      @elseif(session('message') == 'Payment Method Deleted Successfully')
      <label for="checkbox-10 colo_success" style="margin-left: 20px;font-weight: 600;"> {{ trans('message.Payment Method Deleted Successfully') }}</label>

      @elseif(session('message') == 'Income Added Successfully')
      <label for="checkbox-10 colo_success" style="margin-left: 20px;font-weight: 600;"> {{ trans('message.Income Added Successfully') }}</label>
      @elseif(session('message') == 'Income Updated Successfully')
      <label for="checkbox-10 colo_success" style="margin-left: 20px;font-weight: 600;"> {{ trans('message.Income Updated Successfully') }}</label>
      @elseif(session('message') == 'Income Deleted Successfully')
      <label for="checkbox-10 colo_success" style="margin-left: 20px;font-weight: 600;"> {{ trans('message.Income Deleted Successfully') }}</label>

      @elseif(session('message') == 'Expense Added Successfully')
      <label for="checkbox-10 colo_success" style="margin-left: 20px;font-weight: 600;"> {{ trans('message.Expense Added Successfully') }}</label>
      @elseif(session('message') == 'Expense Updated Successfully')
      <label for="checkbox-10 colo_success" style="margin-left: 20px;font-weight: 600;"> {{ trans('message.Expense Updated Successfully') }}</label>
      @elseif(session('message') == 'Expense Deleted Successfully')
      <label for="checkbox-10 colo_success" style="margin-left: 20px;font-weight: 600;"> {{ trans('message.Expense Deleted Successfully') }}</label>

      @elseif(session('message') == 'Vehicle Sell Added Successfully')
      <label for="checkbox-10 colo_success" style="margin-left: 20px;font-weight: 600;"> {{ trans('message.Vehicle Sell Added Successfully') }}</label>
      @elseif(session('message') == 'Vehicle Sell Updated Successfully')
      <label for="checkbox-10 colo_success" style="margin-left: 20px;font-weight: 600;"> {{ trans('message.Vehicle Sell Updated Successfully') }}</label>
      @elseif(session('message') == 'Vehicle Sell Deleted Successfully')
      <label for="checkbox-10 colo_success" style="margin-left: 20px;font-weight: 600;"> {{ trans('message.Vehicle Sell Deleted Successfully') }}</label>

      @elseif(session('message') == 'Part Sell Added Successfully')
      <label for="checkbox-10 colo_success" style="margin-left: 20px;font-weight: 600;"> {{ trans('message.Part Sell Added Successfully') }}</label>
      @elseif(session('message') == 'Part Sell Updated Successfully')
      <label for="checkbox-10 colo_success" style="margin-left: 20px;font-weight: 600;"> {{ trans('message.Part Sell Updated Successfully') }}</label>
      @elseif(session('message') == 'Part Sell Deleted Successfully')
      <label for="checkbox-10 colo_success" style="margin-left: 20px;font-weight: 600;"> {{ trans('message.Part Sell Deleted Successfully') }}</label>

      @elseif(session('message') == 'Compliance Added Successfully')
      <label for="checkbox-10 colo_success" style="margin-left: 20px;font-weight: 600;"> {{ trans('message.Compliance Added Successfully') }}</label>
      @elseif(session('message') == 'Compliance Updated Successfully')
      <label for="checkbox-10 colo_success" style="margin-left: 20px;font-weight: 600;"> {{ trans('message.Compliance Updated Successfully') }}</label>
      @elseif(session('message') == 'Compliance Deleted Successfully')
      <label for="checkbox-10 colo_success" style="margin-left: 20px;font-weight: 600;"> {{ trans('message.Compliance Deleted Successfully') }}</label>

      @elseif(session('message') == 'Email Template Updated Successfully')
      <label for="checkbox-10 colo_success" style="margin-left: 20px;font-weight: 600;"> {{ trans('message.Email Template Updated Successfully') }}</label>

      @elseif(session('message') == 'Customfield Added Successfully')
      <label for="checkbox-10 colo_success" style="margin-left: 20px;font-weight: 600;"> {{ trans('message.Customfield Added Successfully') }}</label>
      @elseif(session('message') == 'Customfield Updated Successfully')
      <label for="checkbox-10 colo_success" style="margin-left: 20px;font-weight: 600;"> {{ trans('message.Customfield Updated Successfully') }}</label>
      @elseif(session('message') == 'Customfield Deleted Successfully')
      <label for="checkbox-10 colo_success" style="margin-left: 20px;font-weight: 600;"> {{ trans('message.Customfield Deleted Successfully') }}</label>

      @elseif(session('message') == 'Branch Added Successfully')
      <label for="checkbox-10 colo_success" style="margin-left: 20px;font-weight: 600;"> {{ trans('message.Branch Added Successfully') }}</label>
      @elseif(session('message') == 'Branch Updated Successfully')
      <label for="checkbox-10 colo_success" style="margin-left: 20px;font-weight: 600;"> {{ trans('message.Branch Updated Successfully') }}</label>
      @elseif(session('message') == 'Branch Deleted Successfully')
      <label for="checkbox-10 colo_success" style="margin-left: 20px;font-weight: 600;"> {{ trans('message.Branch Deleted Successfully') }}</label>

      @elseif(session('message') == 'Jobcard Process Successfully')
      <label for="checkbox-10 colo_success" style="margin-left: 20px;font-weight: 600;"> {{ trans('message.Jobcard Process Successfully') }}</label>
      @elseif(session('message') == 'General Settings Updated Successfully')
      <label for="checkbox-10 colo_success" style="margin-left: 20px;font-weight: 600;"> {{ trans('message.General Settings Updated Successfully') }}</label>
      @elseif(session('message') == 'Other Settings Updated Successfully')
      <label for="checkbox-10 colo_success" style="margin-left: 20px;font-weight: 600;"> {{ trans('message.Other Settings Updated Successfully') }}</label>
      @elseif(session('message') == 'Access Rights Updated Successfully')
      <label for="checkbox-10 colo_success" style="margin-left: 20px;font-weight: 600;"> {{ trans('message.Access Rights Updated Successfully') }}</label>
      @elseif(session('message') == 'Business Hours Updated Successfully')
      <label for="checkbox-10 colo_success" style="margin-left: 20px;font-weight: 600;"> {{ trans('message.Business Hours Updated Successfully') }}</label>
      @elseif(session('message') == 'Stripe Settings Updated Successfully')
      <label for="checkbox-10 colo_success" style="margin-left: 20px;font-weight: 600;"> {{ trans('message.Stripe Settings Updated Successfully') }}</label>
      @elseif(session('message') == 'Business Holiday Added Successfully')
      <label for="checkbox-10 colo_success" style="margin-left: 20px;font-weight: 600;"> {{ trans('message.Business Holiday Added Successfully') }}</label>
      @elseif(session('message') == 'Business Holiday Deleted Successfully')
      <label for="checkbox-10 colo_success" style="margin-left: 20px;font-weight: 600;"> {{ trans('message.Business Holiday Deleted Successfully') }}</label>
      @elseif(session('message') == 'Payment Added Successfully')
      <label for="checkbox-10 colo_success" style="margin-left: 20px;font-weight: 600;"> {{ trans('message.Payment Added Successfully') }}</label>
      @elseif(session('message') == 'Email Settings Updated Successfully')
      <label for="checkbox-10 colo_success" style="margin-left: 20px;font-weight: 600;"> {{ trans('message.Email Settings Updated Successfully') }}</label>
      @elseif(session('message') == 'Test email sent successfully')
      <label for="checkbox-10 colo_success" style="margin-left: 20px;font-weight: 600;"> {{ trans('message.Test email sent successfully') }}</label>
      @elseif(session('message') == 'Your Terms and Condition Submitted Successfully')
      <label for="checkbox-10 colo_success" style="margin-left: 20px;font-weight: 600;"> {{ trans('message.Your Terms and Condition Submitted Successfully') }}</label>
      @elseif(session('message') == 'Stock Updated Successfully')
      <label for="checkbox-10 colo_success" style="margin-left: 20px;font-weight: 600;"> {{ trans('message.Stock Updated Successfully') }}</label>
      @elseif(session('message') == 'Observation Library Added Successfully')
      <label for="checkbox-10 colo_success" style="margin-left: 20px;font-weight: 600;"> {{ trans('message.Observation Library Added Successfully') }}</label>
      @elseif(session('message') == 'Quotation Status Updated Successfully')
      <label for="checkbox-10 colo_success" style="margin-left: 20px;font-weight: 600;"> {{ trans('message.Quotation Status Updated Successfully') }}</label>
      @elseif(session('message') == 'This Record is Duplicate')
      <label for="checkbox-10 colo_success" style="margin-left: 20px;font-weight: 600;"> {{ trans('message.This Record is Duplicate') }}</label>
      @elseif(session('message') == 'Vehicle Model Added Successfully')
      <label for="checkbox-10 colo_success" style="margin-left: 20px;font-weight: 600;"> {{ trans('message.Vehicle Model Added Successfully') }}</label>
      @elseif(session('message') == 'Vehicle Model Updated Successfully')
      <label for="checkbox-10 colo_success" style="margin-left: 20px;font-weight: 600;"> {{ trans('message.Vehicle Model Updated Successfully') }}</label>
      @elseif(session('message') == 'Vehicle Model Deleted Successfully')
      <label for="checkbox-10 colo_success" style="margin-left: 20px;font-weight: 600;"> {{ trans('message.Vehicle Model Deleted Successfully') }}</label>
      @elseif(session('message') == 'Users imported successfully!')
      <label for="checkbox-10 colo_success" style="margin-left: 20px;font-weight: 600;"> {{ trans('message.Users imported successfully!') }}</label>
      @elseif(session('message') == 'Vehicles imported successfully!')
      <label for="checkbox-10 colo_success" style="margin-left: 20px;font-weight: 600;"> {{ trans('message.Vehicles imported successfully!') }}</label>
      @elseif(session('message') == 'Notes Deleted Successfully')
      <label for="checkbox-10 colo_success" style="margin-left: 20px;font-weight: 600;"> {{ trans('message.Notes Deleted Successfully') }}</label>
      @elseif(session('message') == 'Package discovered and registered successfully')
      <label for="checkbox-10 colo_success" style="margin-left: 20px;font-weight: 600;"> {{ trans('message.Package discovered and registered successfully') }}</label>
      @elseif(session('message') == 'No valid packages found in the uploaded zip file.')
      <label for="checkbox-10 colo_success" style="margin-left: 20px;font-weight: 600;"> {{ trans('message.No valid packages found in the uploaded zip file.') }}</label>
      @elseif(session('message') == 'no package dir found.')
      <label for="checkbox-10 colo_success" style="margin-left: 20px;font-weight: 600;"> {{ trans('message.no package dir found.') }}</label>
      @elseif(session('message') == 'Payment settings updated successfully.')
      <label for="checkbox-10 colo_success" style="margin-left: 20px;font-weight: 600;"> {{ trans('message.Payment settings updated successfully.') }}</label>
      @elseif(session('message') == 'Customer Onboarding Settings Updated Successfully.')
      <label for="checkbox-10 colo_success" style="margin-left: 20px;font-weight: 600;"> {{ trans('message.Customer Onboarding Settings Updated Successfully.') }}</label>
        @elseif(session('message') == 'Products imported successfully.')
      <label for="checkbox-10 colo_success" style="margin-left: 20px;font-weight: 600;"> {{ trans('message.Products imported successfully.') }}</label>
      @elseif(session('message'))
      <label for="checkbox-10 colo_success" style="margin-left: 20px;font-weight: 600;"> {{ session('message') }}</label>
      @endif
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close" style="padding: 1rem 0.75rem;"></button>
    </div>
  </div>
</div>
@endif