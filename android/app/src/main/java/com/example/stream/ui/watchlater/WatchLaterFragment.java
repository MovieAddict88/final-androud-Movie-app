package com.example.stream.ui.watchlater;

import android.os.Build;
import android.os.Bundle;
import android.provider.Settings;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;

import androidx.annotation.NonNull;
import androidx.annotation.Nullable;
import androidx.fragment.app.Fragment;
import androidx.recyclerview.widget.GridLayoutManager;

import com.example.stream.databinding.FragmentListBinding;
import com.example.stream.data.model.ContentItem;
import com.example.stream.data.net.ApiClient;
import com.example.stream.ui.home.PostersAdapter;

import java.util.ArrayList;
import java.util.List;
import java.util.Map;

import retrofit2.Call;
import retrofit2.Callback;
import retrofit2.Response;

public class WatchLaterFragment extends Fragment {
    public static WatchLaterFragment newInstance() { return new WatchLaterFragment(); }

    private FragmentListBinding binding;
    private PostersAdapter adapter;

    @Nullable
    @Override
    public View onCreateView(@NonNull LayoutInflater inflater, @Nullable ViewGroup container, @Nullable Bundle savedInstanceState) {
        binding = FragmentListBinding.inflate(inflater, container, false);
        binding.recycler.setLayoutManager(new GridLayoutManager(getContext(), 3));
        adapter = new PostersAdapter(new ArrayList<ContentItem>());
        binding.recycler.setAdapter(adapter);
        load();
        return binding.getRoot();
    }

    private String getDeviceId() {
        return Settings.Secure.getString(requireContext().getContentResolver(), Settings.Secure.ANDROID_ID);
    }

    private void load() {
        ApiClient.getInstance().getWatchLater(getDeviceId()).enqueue(new Callback<Map<String, List<ContentItem>>>() {
            @Override public void onResponse(Call<Map<String, List<ContentItem>>> call, Response<Map<String, List<ContentItem>>> response) {
                if (response.isSuccessful() && response.body() != null) {
                    List<ContentItem> list = response.body().get("data");
                    if (list == null) list = new ArrayList<>();
                    adapter.submit(list);
                }
            }
            @Override public void onFailure(Call<Map<String, List<ContentItem>>> call, Throwable t) {}
        });
    }
}
